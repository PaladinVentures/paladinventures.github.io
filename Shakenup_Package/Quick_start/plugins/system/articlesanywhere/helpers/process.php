<?php
/**
 * Plugin Helper File: Process
 *
 * @package         Articles Anywhere
 * @version         3.7.0
 *
 * @author          Peter van Westen <peter@nonumber.nl>
 * @link            http://www.nonumber.nl
 * @copyright       Copyright © 2014 NoNumber All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

class plgSystemArticlesAnywhereHelperProcess
{
	var $helpers = array();
	var $params = null;
	var $aid = null;

	public function __construct()
	{
		require_once __DIR__ . '/helpers.php';
		$this->helpers = plgSystemArticlesAnywhereHelpers::getInstance();
		$this->params = $this->helpers->getParams();

		$bts = '((?:<p(?: [^>]*)?>)?)((?:\s*<br ?/?>)?\s*)';
		$bte = '(\s*(?:<br ?/?>\s*)?)((?:</p>)?)';
		$this->params->tags = '(' . preg_quote($this->params->article_tag, '#')
			. ')';
		$this->params->regex = '#'
			. $bts . '\{' . $this->params->tags . '(?:(?:\s|&nbsp;|&\#160;)([^\}]*))?\}' . $bte
			. '(.*?)'
			. $bts . '\{/\3\}' . $bte
			. '#s';
		$this->params->breaks_start = $bts;
		$this->params->breaks_end = $bte;

		$db = JFactory::getDBO();
		$selects = $db->getTableColumns('#__content');
		if (is_array($selects))
		{
			unset($selects['introtext']);
			unset($selects['fulltext']);
			$selects = array_keys($selects);
			$this->params->dbselects_content = $selects;
		}


		$this->aid = JFactory::getUser()->getAuthorisedViewLevels();
	}

	public function processArticles(&$string, $area = 'articles', $art = null)
	{

		$regex_close = '#\{/' . $this->params->tags . '\}#si';

		if (!preg_match($regex_close, $string))
		{
			return;
		}

		if (@preg_match($regex_close . 'u', $string))
		{
			$regex_close .= 'u';
		}
		$regex = $this->params->regex;
		if (@preg_match($regex . 'u', $string))
		{
			$regex .= 'u';
		}

		$matches = array();
		$count = 0;
		while ($count++ < 10 && preg_match($regex_close, $string) && preg_match_all($regex, $string, $matches, PREG_SET_ORDER) > 0)
		{
			foreach ($matches as $match)
			{

				$this->replaceArticleTag($string, $match, $art);
			}
			$matches = array();
		}
	}

	private function replaceArticleTag(&$string, &$match, $art = null)
	{
		$parts = explode('|', $match['4']);
		$match['4'] = trim(array_shift($parts));
		$ignores = array();

		foreach ($parts as $p)
		{
			if (strpos($p, '=') === false)
			{
				continue;
			}

			list($key, $val) = explode('=', $p, 2);
			$key = trim($key);
			$val = trim($val);

			if (!in_array($key, array('ignore_language', 'ignore_access', 'ignore_state')))
			{
				continue;
			}

			$val = str_replace(array('\{', '\}'), array('{', '}'), $val);
			$ignores[$key] = $val;
		}

		$html = $this->processMatch($string, $art, $match, $ignores);
		$string = nnText::strReplaceOnce($match['0'], $html, $string);
	}


	private function processMatch(&$string, &$art, &$match, &$ignores, &$count = 0, $first = 0, $last = 0)
	{
		if ($this->params->message != '')
		{
			if ($this->params->place_comments)
			{
				return $this->params->comment_start . $this->params->message_start . $this->params->message . $this->params->message_end . $this->params->comment_end;
			}

			return '';
		}

		$p1_start = $match['1'];
		$br1a = $match['2'];
		//$type		= $match['3'];
		$id = $match['4'];
		$br1b = $match['5'];
		$p1_end = $match['6'];
		$html = $match['7'];
		$p2_start = $match['8'];
		$br2a = $match['9'];
		// end tag
		$br2b = $match['10'];
		$p2_end = $match['11'];

		$html = trim($html);
		preg_match('#^' . $this->params->breaks_start . '(.*?)' . $this->params->breaks_end . '$#s', trim($html), $text_match);

		$p1_start = ($p1_end || $text_match['1']) ? '' : $p1_start;
		$p2_end = ($p2_start || $text_match['5']) ? '' : $p2_end;

		if (strpos($string, '{/div}') !== false && preg_match('#^' . $this->params->breaks_start . '(\{div[^\}]*\})' . $this->params->breaks_end . '(.*?)' . $this->params->breaks_start . '(\{/div\})' . $this->params->breaks_end . '#s', $html, $div_match))
		{
			if ($div_match['1'] && $div_match['5'])
			{
				$div_match['1'] = '';
			}

			if ($div_match['7'] && $div_match['11'])
			{
				$div_match['11'] = '';
			}

			$html = $div_match['2'] . $div_match['3'] . $div_match['4'] . $div_match['1'] . $div_match['6'] . $div_match['11'] . $div_match['8'] . $div_match['9'] . $div_match['10'];
		}

		$type = 'article';
		if (strpos($id, ':') !== false)
		{
			$type = explode(':', $id, 2);
			if ($type['0'] == 'k2')
			{
				$id = trim($type['1']);
			}
		}

		$html = $this->processArticle($id, $art, $html, $type, $ignores, $count, $first, $last);

		if ($this->params->place_comments)
		{
			$html = $this->params->comment_start . $html . $this->params->comment_end;
		}

		$html = $p1_start . $br1a . $br1b . $html . $br2a . $br2b . $p2_end;

		nnText::fixHtmlTagStructure($html);

		return $html;
	}

	private function processArticle($id, $art, $text = '', $type = 'article', $ignores = array(), &$count = 0, $first = 0, $last = 0, $firstpass = 0)
	{
		if ($firstpass)
		{
			// first pass: search for normal tags and tags around tags
			$regex = '#\{(/?(?:[^\}]*\{[^\}]*\})*[^\}]*)\}#si';
		}
		else
		{
			// do second pass
			$text = $this->processArticle($id, $art, $text, $type, $ignores, $count, $first, $last, 1);

			$regex_close = '#\{/' . $this->params->tags . '\}#si';
			if (preg_match($regex_close, $text))
			{
				return $text;
			}
			// second pass: only search for normal tags
			$regex = '#\{(/?[^\{\}]+)\}#si';
		}

		if (!preg_match_all($regex, $text, $matches, PREG_SET_ORDER))
		{
			return $text;
		}

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		$type = ($type == 'k2' && !$this->params->dbselects_k2) ? '' : $type;
		$selects = ($type == 'k2') ? $this->params->dbselects_k2 : $this->params->dbselects_content;

		if (in_array($id, array('current', 'self', '{id}', '{title}', '{alias}')))
		{
			if (isset($art->id))
			{
				$id = $art->id;
			}
			else if (isset($art->link) && preg_match('#&amp;id=([0-9]*)#', $art->link, $match))
			{
				$id = $match['1'];
			}
			else if ($this->params->option == 'com_content' && JFactory::getApplication()->input->get('view') == 'article')
			{
				$id = JFactory::getApplication()->input->getInt('id', 0);
			}
		}

		foreach ($matches as $match)
		{
			$data = trim($match['1']);

			switch (true)
			{
				case (strpos($data, 'intro') !== false):
					$selects[] = 'introtext';
					break;

				case (strpos($data, 'full') !== false):
					$selects[] = 'fulltext';
					break;

				case (strpos($data, 'text') !== false):
					$selects[] = 'introtext';
					$selects[] = 'fulltext';
					break;

			}
		}

		$selects = array_unique($selects);
		$selects = 'a.`' . implode('`, a.`', $selects) . '`';
		$query->select($selects);

		if ($type == 'article')
		{
			$query->select('CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END AS slug')
				->select('CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(":", c.id, c.alias) ELSE c.id END AS catslug')
				->select('c.parent_id AS parent')
				->join('LEFT', '#__categories AS c ON c.id = a.catid');
		}

		$table = 'content';
		$query->from('#__' . $table . ' as a');

		$where = 'a.title = ' . $db->quote(NNText::html_entity_decoder($id));
		$where .= ' OR a.alias = ' . $db->quote(NNText::html_entity_decoder($id));
		if (is_numeric($id))
		{
			$where .= ' OR a.id = ' . $id;
		}
		$query->where('(' . $where . ')');

		$ignore_language = isset($ignores['ignore_language']) ? $ignores['ignore_language'] : $this->params->ignore_language;
		if (!$ignore_language)
		{
			$query->where('a.language in (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
		}

		$ignore_state = isset($ignores['ignore_state']) ? $ignores['ignore_state'] : $this->params->ignore_state;
		if (!$ignore_state)
		{
			$jnow = JFactory::getDate();
			$now = $jnow->toSQL();
			$nullDate = $db->getNullDate();

			$where = 'a.state = 1';

			$query->where($where)
				->where('( a.publish_up = ' . $db->quote($nullDate) . ' OR a.publish_up <= ' . $db->quote($now) . ' )')
				->where('( a.publish_down = ' . $db->quote($nullDate) . ' OR a.publish_down >= ' . $db->quote($now) . ' )');
		}

		$ignore_access = isset($ignores['ignore_access']) ? $ignores['ignore_access'] : $this->params->ignore_access;
		if (!$ignore_access)
		{
			$query->where('a.access IN(' . implode(', ', $this->aid) . ')');
		}

		$query->order('a.ordering');

		$db->setQuery($query);
		$article = $db->loadObject();

		if (!$article)
		{
			return '<!-- ' . JText::_('AA_ACCESS_TO_ARTICLE_DENIED') . ' -->';
		}

		self::addParams(
			$article,
			json_decode(
				isset($article->attribs)
					? $article->attribs
					: $article->params
			)
		);

		if (isset($article->images))
		{
			self::addParams($article, json_decode($article->images));
		}

		if (isset($article->urls))
		{
			self::addParams($article, json_decode($article->urls));
		}

		$helper = 'tags';

		$this->helpers->get($helper)->handleIfStatements($text, $article, $count, $first, $last);

		if (!preg_match_all($regex, $text, $matches, PREG_SET_ORDER))
		{
			return $text;
		}

		$this->helpers->get($helper)->replaceTags($text, $matches, $article, $count);

		return $text;
	}


	function addParams(&$article, $params)
	{
		if (!$params ||
			(!is_object($params) && !is_array($params))
		)
		{
			return;
		}

		foreach ($params as $key => $val)
		{
			if (isset($article->$key))
			{
				continue;
			}

			$article->$key = $val;
		}
	}
}
