<?php
/**
 * Plugin Helper File: Tags
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

class plgSystemArticlesAnywhereHelperTags
{
	var $helpers = array();
	var $params = null;
	var $config = null;
	var $article = null;
	var $images = null;

	public function __construct()
	{
		require_once __DIR__ . '/helpers.php';
		$this->helpers = plgSystemArticlesAnywhereHelpers::getInstance();
		$this->params = $this->helpers->getParams();

		$this->config = JComponentHelper::getParams('com_content');
	}

	public function handleIfStatements(&$string, &$article, &$count = 0, $first = 0, $last = 0)
	{
		if (preg_match_all(
				'#\{if:.*?\{/if\}#si',
				$string,
				$matches,
				PREG_SET_ORDER
			) < 1
		)
		{
			return;
		}

		if (strpos($string, 'text') !== false)
		{
			$article->text = (isset($article->introtext) ? $article->introtext : '')
				. (isset($article->introtext) ? $article->fulltext : '');
		}

		foreach ($matches as $match)
		{
			if (preg_match_all(
					'#\{(if|else *?if|else)(?:\:([^\}]+))?\}(.*?)(?=\{(?:else|\/if))#si',
					$match['0'],
					$ifs,
					PREG_SET_ORDER
				) < 1
			)
			{
				continue;
			}

			$replace = $this->getIfResult($ifs, $article, $count, $first, $last);

			// replace if block with the IF value
			$string = nnText::strReplaceOnce($match['0'], $replace, $string);
		}
	}

	private function getIfResult(&$matches, &$article, &$count = 0, $first = 0, $last = 0)
	{
		foreach ($matches as $if)
		{
			if (!$this->passIfStatement($if, $article, $count, $first, $last))
			{
				continue;
			}

			return $if['3'];
		}

		return '';
	}

	private function passIfStatement(&$if, &$article, &$count = 0, $first = 0, $last = 0)
	{
		$statement = trim($if['2']);

		if (trim($if['1']) == 'else' && $statement == '')
		{
			return true;
		}

		if ($statement == '')
		{
			return false;
		}

		$php = html_entity_decode($statement);
		$php = str_replace('=', '==', $php);

		// replace keys with $article->key
		$php = '$article->' . preg_replace('#\s*(&&|&&|\|\|)\s*#', ' \1 $article->', $php);

		// fix negative keys from $article->!key to !$article->key
		$php = str_replace('$article->!', '!$article->', $php);

		// replace back count/first/last variables
		$php = str_replace('$article->count', $count, $php);
		$php = str_replace('$article->first', (int) $first, $php);
		$php = str_replace('$article->last', (int) $last, $php);

		// Place statement in return check
		$php = 'return ( ' . $php . ' ) ? 1 : 0;';

		// Trim the text that needs to be checked and replace weird spaces
		$php = preg_replace(
			'#(\$article->[a-z0-9-_]*)#',
			'trim(str_replace(chr(194) . chr(160), " ", \1))',
			$php
		);

		$temp_PHP_func = create_function('&$article', $php);

		// evaluate the script
		// but without using the the evil eval
		ob_start();
		$pass = $temp_PHP_func($article);
		unset($temp_PHP_func);
		ob_end_clean();

		return $pass;
	}

	public function replaceTags(&$text, &$matches, &$article, &$count)
	{
		$this->article = $article;
		foreach ($matches as $match)
		{
			$string = $this->processTag(trim($match['1']), $count);
			if ($string === false)
			{
				continue;
			}

			$text = str_replace($match['0'], $string, $text);
		}
	}

	public function processTag($data, &$count)
	{
		$data = explode(':', $data, 2);

		$tag = trim($data['0']);
		$extra = isset($data['1']) ? trim($data['1']) : '';

		switch (true)
		{
			// Link closing tag
			case ($tag == '/link'):
				return '</a>';

			// Counter
			case ($tag == 'count' || $tag == 'counter'):
				return $count;

			// Div closing tag
			case ($tag == '/div'):
				return '</div>';

			// Div
			case ($tag == 'div' || strpos($tag, 'div ') === 0):
				return $this->processTagDiv($tag, $extra);

			// URL
			case ($tag == 'url'):
				return $this->getArticleUrl();

			// Link tag
			case ($tag == 'link'):
				return $this->processTagLink();

			// Readmore link
			case (strpos($tag, 'readmore') === 0):
				return $this->processTagReadmore($extra);

			// Text
			case (
				(strpos($tag, 'text') === 0)
				|| (strpos($tag, 'intro') === 0)
				|| (strpos($tag, 'full') === 0)
			):
				return $this->processTagText($tag, $extra);

			// Intro image
			case ($tag == 'image-intro'):
				return $this->processTagImageIntro();

			// Fulltext image
			case ($tag == 'image-fulltext'):
				return $this->processTagImageFulltext();


			// Database values
			case (ctype_alnum(str_replace(array('-', '_'), '', $tag))):
				return $this->processTagDatabase($tag, $extra);

			default:
				return false;
		}
	}

	public function processTagDiv($tag, $extra)
	{
		if ($tag != 'div')
		{
			$extra = str_replace('div ', '', $tag)
				. ':'
				. $extra;
		}

		if (!$extra)
		{
			return '<div>';
		}

		$string = '';

		$extra = explode('|', $extra);
		$extras = new stdClass;
		foreach ($extra as $e)
		{
			if (strpos($e, ':') !== false)
			{
				list($key, $val) = explode(':', $e, 2);
				$extras->$key = $val;
			}
		}

		if (isset($extras->class))
		{
			$string .= 'class="' . $extras->class . '"';
		}

		$style = array();

		if (isset($extras->width))
		{
			if (is_numeric($extras->width))
			{
				$extras->width .= 'px';
			}
			$style[] = 'width:' . $extras->width;
		}

		if (isset($extras->height))
		{
			if (is_numeric($extras->height))
			{
				$extras->height .= 'px';
			}
			$style[] = 'height:' . $extras->height;
		}

		if (isset($extras->align))
		{
			$style[] = 'float:' . $extras->align;
		}
		else if (isset($extras->float))
		{
			$style[] = 'float:' . $extras->float;
		}

		if (!empty($style))
		{
			$string .= ' style="' . implode(';', $style) . ';"';
		}

		return trim('<div ' . trim($string)) . '>';
	}

	public function processTagReadmore($extra)
	{
		if (!$link = $this->getArticleUrl())
		{
			return false;
		}

		// load the content language file
		JFactory::getLanguage()->load('com_content');

		$extra = explode('|', $extra);

		$class = isset($extra['1'])
			? trim($extra['1'])
			: 'readmore';

		$readmore = $this->getReadMoreText($extra);

		if ($class == 'readmore')
		{
			return '<p class="' . $class . '"><a href="' . $link . '">' . $readmore . '</a></p>';
		}

		return '<a class="' . $class . '" href="' . $link . '">' . $readmore . '</a>';
	}

	public function getReadMoreText($extra)
	{
		if (!empty($extra) && trim($extra['0']))
		{
			$text = JText::sprintf(trim($extra['0']), $this->article->title);

			return $text ?: $extra['0'];
		}

		switch (true)
		{
			case (isset($this->article->alternative_readmore) && $this->article->alternative_readmore) :
				$text = $this->article->alternative_readmore;
				break;
			case (!$this->config->get('show_readmore_title', 0)) :
				$text = JText::_('COM_CONTENT_READ_MORE_TITLE');
				break;
			default:
				$text = JText::_('COM_CONTENT_READ_MORE');
				break;
		}

		if (!$this->config->get('show_readmore_title', 0))
		{
			return $text;

		}

		return $text . JHtml::_('string.truncate', ($this->article->title), $this->config->get('readmore_limit'));
	}

	public function processTagLink()
	{
		if (!$link = $this->getArticleUrl())
		{
			return false;
		}

		return '<a href="' . $link . '">';
	}

	public function processTagText($tag, $extra)
	{
		switch (true)
		{
			case (strpos($tag, 'intro') === 0):
				if (!isset($this->article->introtext))
				{
					return false;
				}
				$this->article->text = $this->article->introtext;
				break;

			case (strpos($tag, 'full') === 0):
				if (!isset($this->article->fulltext))
				{
					return false;
				}

				$this->article->text = $this->article->fulltext;
				break;

			case (strpos($tag, 'text') === 0):
				$this->article->text = (isset($this->article->introtext) ? $this->article->introtext : '')
					. (isset($this->article->introtext) ? $this->article->fulltext : '');
				break;
		}

		if ($this->article->text == '')
		{
			return '';
		}

		$string = $this->article->text;

		if (!$extra)
		{
			return $string;
		}

		$attribs = explode(':', $extra);

		$max = 0;
		$strip = 0;
		$noimages = 0;
		foreach ($attribs as $attrib)
		{
			$attrib = trim($attrib);
			switch ($attrib)
			{
				case 'strip':
					$strip = 1;
					break;
				case 'noimages':
					$noimages = 1;
					break;
				default:
					$max = $attrib;
					break;
			}
		}

		$word_limit = (strpos($max, 'word') !== false);
		if ($strip)
		{
			// remove pagenavcounter
			$string = preg_replace('#(<' . 'div class="pagenavcounter">.*?</div>)#si', ' ', $string);
			// remove pagenavbar
			$string = preg_replace('#(<' . 'div class="pagenavbar">(<div>.*?</div>)*</div>)#si', ' ', $string);
			// remove inline scripts
			$string = preg_replace('#(<' . 'script[^a-z0-9].*?</script>)#si', ' ', $string);
			$string = preg_replace('#(<' . 'noscript[^a-z0-9].*?</noscript>)#si', ' ', $string);
			// remove inline styles
			$string = preg_replace('#(<' . 'style[^a-z0-9].*?</style>)#si', ' ', $string);
			// remove other tags
			$string = preg_replace('#(<' . '/?[a-z][a-z0-9]?.*?>)#si', ' ', $string);
			// remove double whitespace
			$string = trim(preg_replace('#\s+#s', ' ', $string));

			if ($max)
			{
				$orig_len = strlen($string);
				if ($word_limit)
				{
					// word limit
					$string = trim(preg_replace(
						'#^(([^\s]+\s*){' . (int) $max . '}).*$#s',
						'\1',
						$string
					));
					if (strlen($string) < $orig_len)
					{
						if (preg_match('#[^a-z0-9]$#si', $string))
						{
							$string .= ' ';
						}
						if ($this->params->use_ellipsis)
						{
							$string .= '...';
						}
					}
				}
				else
				{
					// character limit
					$max = (int) $max;
					if ($max < $orig_len)
					{
						if (function_exists('mb_substr'))
						{
							$string = rtrim(mb_substr($string, 0, ($max - 3), 'utf-8'));
						}
						else
						{
							$string = rtrim(substr($string, 0, ($max - 3)));
						}

						if (preg_match('#[^a-z0-9]$#si', $string))
						{
							$string .= ' ';
						}

						if ($this->params->use_ellipsis)
						{
							$string .= '...';
						}
					}
				}
			}
		}
		else if ($noimages)
		{
			// remove images
			$string = preg_replace(
				'#(<p><' . 'img\s.*?></p>|<' . 'img\s.*?>)#si',
				' ',
				$string
			);
		}

		if (!$strip && $max && ($word_limit || (int) $max < strlen($string)))
		{
			$max = (int) $max;

			// store pagenavcounter & pagenav (exclude from count)
			preg_match('#<' . 'div class="pagenavcounter">.*?</div>#si', $string, $pagenavcounter);
			$pagenavcounter = isset($pagenavcounter['0']) ? $pagenavcounter['0'] : '';
			if ($pagenavcounter)
			{
				$string = str_replace($pagenavcounter, '<!-- ARTA_PAGENAVCOUNTER -->', $string);
			}
			preg_match('#<' . 'div class="pagenavbar">(<div>.*?</div>)*</div>#si', $string, $pagenav);
			$pagenav = isset($pagenav['0']) ? $pagenav['0'] : '';
			if ($pagenav)
			{
				$string = str_replace($pagenav, '<!-- ARTA_PAGENAV -->', $string);
			}

			// add explode helper strings around tags
			$explode_str = '<!-- ARTA_TAG -->';
			$string = preg_replace(
				'#(<\/?[a-z][a-z0-9]?.*?>|<!--.*?-->)#si',
				$explode_str . '\1' . $explode_str,
				$string
			);

			$str_array = explode($explode_str, $string);

			$string = array();
			$tags = array();
			$count = 0;
			$is_script = 0;
			foreach ($str_array as $i => $str_part)
			{
				if (fmod($i, 2))
				{
					// is tag
					$string[] = $str_part;
					preg_match(
						'#^<(\/?([a-z][a-z0-9]*))#si',
						$str_part,
						$tag
					);
					if (!empty($tag))
					{
						if ($tag['1'] == 'script')
						{
							$is_script = 1;
						}

						if (!$is_script
							// only if tag is not a single html tag
							&& (strpos($str_part, '/>') === false)
							// just in case single html tag has no closing character
							&& !in_array($tag['2'], array('area', 'br', 'hr', 'img', 'input', 'param'))
						)
						{
							$tags[] = $tag['1'];
						}

						if ($tag['1'] == '/script')
						{
							$is_script = 0;
						}
					}
				}
				else if ($is_script)
				{
					$string[] = $str_part;
				}
				else
				{
					if ($word_limit)
					{
						// word limit
						if ($str_part)
						{
							$words = explode(' ', trim($str_part));
							$word_count = count($words);
							if ($max < ($count + $word_count))
							{
								$words_part = array();
								$word_count = 0;
								foreach ($words as $word)
								{
									if ($word)
									{
										$word_count++;
									}
									if ($max < ($count + $word_count))
									{
										break;
									}
									$words_part[] = $word;
								}
								$string_part = rtrim(implode(' ', $words_part));
								if (preg_match('#[^a-z0-9]$#si', $string_part))
								{
									$string_part .= ' ';
								}
								if ($this->params->use_ellipsis)
								{
									$string_part .= '...';
								}
								$string[] = $string_part;
								break;
							}
							$count += $word_count;
						}
						$string[] = $str_part;
					}
					else
					{
						// character limit
						if ($max < ($count + strlen($str_part)))
						{
							// strpart has to be cut off
							$maxlen = $max - $count;
							if ($maxlen < 3)
							{
								$string_part = '';
								if (preg_match('#[^a-z0-9]$#si', $str_part))
								{
									$string_part .= ' ';
								}
								if ($this->params->use_ellipsis)
								{
									$string_part .= '...';
								}
								$string[] = $string_part;
							}
							else
							{
								if (function_exists('mb_substr'))
								{
									$string_part = rtrim(mb_substr($str_part, 0, ($max - 3), 'utf-8'));
								}
								else
								{
									$string_part = rtrim(substr($str_part, 0, ($max - 3)));
								}

								if (preg_match('#[^a-z0-9]$#si', $string_part))
								{
									$string_part .= ' ';
								}

								if ($this->params->use_ellipsis)
								{
									$string_part .= '...';
								}

								$string[] = $string_part;
							}
							break;
						}
						$count += strlen($str_part);
						$string[] = $str_part;
					}
				}
			}

			// revers sort open tags
			krsort($tags);
			$tags = array_values($tags);
			$count = count($tags);

			for ($i = 0; $i < 3; $i++)
			{
				foreach ($tags as $ti => $tag)
				{
					if ($tag['0'] == '/')
					{
						for ($oi = $ti + 1; $oi < $count; $oi++)
						{
							if (!isset($tags[$oi]))
							{
								unset($tags[$ti]);
								break;
							}
							$opentag = $tags[$oi];
							if ($opentag == $tag)
							{
								break;
							}
							if ('/' . $opentag == $tag)
							{
								unset($tags[$ti]);
								unset($tags[$oi]);
								break;
							}
						}
					}
				}
			}

			foreach ($tags as $tag)
			{
				// add closing tag to end of string
				if ($tag['0'] != '/')
				{
					$string[] = '</' . $tag . '>';
				}
			}
			$string = implode('', $string);

			$string = str_replace(array('<!-- ARTA_PAGENAVCOUNTER -->', '<!-- ARTA_PAGENAV -->'), array($pagenavcounter, $pagenav), $string);
		}

		// Fix links in pagination to point to the included article instead of the main article
		// This doesn't seem to work correctly and causes issues with other links in the article
		// So commented out untill I find a better solution
		/*if ($art && isset($art->id) && $art->id) {
			$string = str_replace('view=article&amp;id=' . $art->id, 'view=article&amp;id=' . $this->article->id, $string);
		}*/

		return $string;
	}

	public function processTagImageIntro()
	{
		if (!isset($this->article->image_intro))
		{
			return '';
		}

		$class = 'img-intro-' . htmlspecialchars($this->article->float_intro);
		$caption = $this->article->image_intro_caption ? 'class="caption" title="' . htmlspecialchars($this->article->image_intro_caption) . '" ' : '';
		$src = htmlspecialchars($this->article->image_intro);
		$alt = htmlspecialchars($this->article->image_intro_alt);

		return '<div class="' . $class . '"><img ' . $caption . 'src="' . $src . '" alt="' . $alt . '"/></div>';
	}

	public function processTagImageFulltext()
	{
		if (!isset($this->article->image_fulltext))
		{
			return '';
		}

		$class = 'img-fulltext-' . htmlspecialchars($this->article->float_fulltext);
		$caption = $this->article->image_fulltext_caption ? 'class="caption" title="' . htmlspecialchars($this->article->image_fulltext_caption) . '" ' : '';
		$src = htmlspecialchars($this->article->image_fulltext);
		$alt = htmlspecialchars($this->article->image_fulltext_alt);

		return '<div class="' . $class . '"><img ' . $caption . 'src="' . $src . '" alt="' . $alt . '"/></div>';
	}


	public function processTagDatabase($tag, $extra)
	{
		// Get data from db columns
		if (!isset($this->article->$tag))
		{
			return false;
		}

		$string = $this->article->$tag;

		// Convert string if it is a date
		$string = $this->convertDateToString($string, $extra);

		return $string;
	}

	public function convertDateToString($string, $extra)
	{
		// Check if string could be a date
		if ((strpos($string, '-') == false)
			|| preg_match('#[a-z]#i', $string)
			|| !strtotime($string)
		)
		{
			return $string;
		}

		if (!$extra)
		{
			$extra = JText::_('DATE_FORMAT_LC2');
		}

		if (strpos($extra, '%') !== false)
		{
			$extra = NNText::dateToDateFormat($extra);
		}

		return JHtml::_('date', $string, $extra);
	}

	public function canEdit()
	{
		$user = JFactory::getUser();
		if ($user->get('guest'))
		{
			return false;
		}

		$userId = $user->get('id');
		$asset = 'com_content.article.' . $this->article->id;

		// Check general edit permission first.
		if ($user->authorise('core.edit', $asset))
		{
			return true;
		}

		// Now check if edit.own is available.
		if (empty($userId) || $user->authorise('core.edit.own', $asset))
		{
			return false;
		}

		// Check for a valid user and that they are the owner.
		if ($userId != $this->article->created_by)
		{
			return false;
		}

		return true;
	}

	public function getArticleUrl()
	{
		if (isset($this->article->url))
		{
			return $this->article->url;
		}

		if (!isset($this->article->id))
		{
			return false;
		}

		JLoader::register('ContentHelperRoute', JPATH_SITE . '/components/com_content/helpers/route.php');
		$this->article->url = ContentHelperRoute::getArticleRoute($this->article->id, $this->article->catid, $this->article->language);

		return $this->article->url;
	}

	public function getArticleEditUrl()
	{
		if (isset($this->article->editurl))
		{
			return $this->article->editurl;
		}

		if (!isset($this->article->id))
		{
			return false;
		}

		$this->article->editurl = '';

		if (!$this->canEdit())
		{
			return false;
		}

		$uri = JURI::getInstance();
		$this->article->editurl = JRoute::_('index.php?option=com_content&task=article.edit&a_id=' . $this->article->id . '&return=' . base64_encode($uri));

		return $this->article->editurl;
	}

	public function getArticleImages()
	{
		if (!is_array($this->images))
		{
			$article_text = (isset($this->article->introtext) ? $this->article->introtext : '')
				. (isset($this->article->fulltext) ? $this->article->fulltext : '');

			preg_match_all(
				'#<img\s[^>]*src=([\'"])(.*?)\1[^>]*>#si',
				$article_text,
				$this->images,
				PREG_SET_ORDER
			);
		}

		return $this->images;
	}

}
