<?php
/**
 * Extension to ParsedownToC Extension/Plugin for Parsedown.
 * ============================================================================
 * It changes the way the ToC plugin behaves to delay parsing of heads till 
 * it encounters a Heading with an id that is specified as an agreed signal
 * 
 * It also changes link behavior in parsedown so external links are in new window
 *
 * @author      DigitalSorceress (https://github.com/DigitalSorceress/)
 * @package     Parsedown ^1.8.0 (https://github.com/erusev/parsedown)
 * @php         ^8.0
 * @see         HowTo: 
 * @license     MIT: 
*/

class ParsedownExtraTocTocTop extends ParsedownToC
{
    /**
     * ------------------------------------------------------------------------
     *  Constants.
     * ------------------------------------------------------------------------
     */
    const version = '1.0.1'; 
	const VERSION_PARSEDOWN_TOC_REQUIRED = '1.1.2'; // tested against 1.1.2 and 1.4.0

    protected $tocTopId = 'toctop';  
    protected $tocReached = false; // set this to true to prevent skipping header items before TOC


    /**
     * Version requirement check.
     */
    public function __construct()
    {
        if (version_compare( parent::VERSION, self::VERSION_PARSEDOWN_TOC_REQUIRED) < 0) {
            $msg_error  = 'Version Error.' . PHP_EOL;
            $msg_error .= '  ParsedownExtraTocDigit Extension requires a later version of ParsedownToC.' . PHP_EOL;
            $msg_error .= '  - Current version : ' . \ParsedownToC::version . PHP_EOL;
            $msg_error .= '  - Required version: ' . self::VERSION_PARSEDOWN_TOC_REQUIRED . PHP_EOL;
            throw new Exception($msg_error);
        }

        parent::__construct();
    }

    /**
     * ------------------------------------------------------------------------
     * SETTERS (custom config Parameters)
     * ------------------------------------------------------------------------
     */
    
    /**
     * ## When enabled, you can have the TOC Skip heading blocks that appear at 
     * above a preset {#tagId}
     * - Defaults work as the original ParsedownTocExtension works (all headings
     * from the beginning of the file are included)
     * - default tag is {#top} you can override by setting optional tagid
     * - set tagid to a string with no spaces and without the #
     * @param bool $enabled - default: false
     * @param string $customIdForTocStart - default: 'toc'
     * @return void
     */
    function setDelayedToc($enabled, $customIdForTocStart = 'toctop')
    {
        $this->tocReached = !$enabled;
        $this->tocTopId = $customIdForTocStart;
    }

    function setAllLinksInNewWindow($enabled) {
        $this->allLinksInNewWindow = $enabled;
    }
    protected $allLinksInNewWindow = false;

    function setExtLinksInNewWindow($enabled) {
        $this->extLinksInNewWindow = $enabled;
    }
    protected $extLinksInNewWindow = false;

    /**
     * DigitalSorceress EXTENSION NOTE: 
     * so, this is where I need to explicitly avaoid calling setContentsList
     * until my conditions are met. This means I have to take in the blockHeader
     * in total and copy its code so I can intercept where I need.
     * 
     * ORIGINAL NOTE 
     * Heading process.
     * Creates heading block element and stores to the ToC list. It overrides
     * the parent method: \Parsedown::blockHeader() and returns $Block array if
     * the $Line is a heading element.
     *
     * @param  array $Line  Array that Parsedown detected as a block type element.
     * @return void|array   Array of Heading Block.
     */
    protected function blockHeader($Line)
    {
        // Use parent blockHeader method to process the $Line to $Block
        $Block = DynamicParent::blockHeader($Line);

        if (! empty($Block)) {
            // Get the text of the heading
            if (isset($Block['element']['handler']['argument'])) {
                // Compatibility with old Parsedown Version
                $text = $Block['element']['handler']['argument'];
            }
            if (isset($Block['element']['text'])) {
                // Current Parsedown
                $text = $Block['element']['text'];
            }

            // Get the heading level. Levels are h1, h2, ..., h6
            $level = $Block['element']['name'];

            // Get the anchor of the heading to link from the ToC list
            $id = isset($Block['element']['attributes']['id']) ?
                $Block['element']['attributes']['id'] : $this->createAnchorID($text);

            // Set attributes to head tags
            $Block['element']['attributes'] = array(
                'id'   => $id,
                'name' => $id,
            );


            // We are NOT adding unless the "frontmatter" is concluded
            if ($this->tocReached ) {
                // Add/stores the heading element info to the ToC list
                $this->setContentsList(array(
                    'text'  => $text,
                    'id'    => $id,
                    'level' => $level
                ));
            } elseif ($id == $this->tocTopId) {
                //// NOTE we need to test this after the content list
                //// So that we don't start including till after that first h3
                $this->tocReached = true;
            }
            return $Block;
        }
    }

    /**
     * DigitalSorceress override of inlineLink to add target attribute as needed 
     *
     * I'm overriding the inlinLink, applying my simple logic to it: if there is 
     * an href and allLinksInNewWindow is enabled OR if it starts with 'http' 
     * (which nicely covers both http and https) AND my extLinksInNewWindow is 
     * enabled, then I set target to _blank. All other targets are set to 
     * _self. 
     * 
     * Only done if href found - no target needed for anchor tags without href
     *  
      * @param mixed $Excerpt 
      * @return void|array{extent: int, element: array{name: string, handler: array{function: string, argument: null|string, destination: string}, nonNestables: string[], attributes: array{href: mixed, title: mixed}}} 
      */
    protected function inlineLink($Excerpt)
    {
        // DynamicParent is Toc's way of either doing parsedown or parsedown extended
        $Link = DynamicParent::inlineLink($Excerpt);
        
        $url = $Link['element']['attributes']['href'] ?? null;
        if ($url != null) {
            $target = '_self';

            ## BEGIN added by DigitalSorceress https://github.com/DigitalSorceress/parsedown
            if ($this->allLinksInNewWindow || ($this->extLinksInNewWindow && str_starts_with($url, 'http'))) {
            $target = '_blank';
            } 
            $Link['element']['attributes']['target'] = $target;
        }
        return $Link;
    }

    /**
     * DigitalSorceress override of inlineUrl to add target attribute as needed 
     * 
     * I'm overriding the inlinLink, applying my simple logic to it: if there is 
     * an href and allLinksInNewWindow is enabled OR if it starts with 'http' 
     * (which nicely covers both http and https) AND my extLinksInNewWindow is 
     * enabled, then I set target to _blank. All other targets are set to 
     * _self. 
     * 
     * Only done if href found - no target needed for anchor tags without href 
     * 
     * @param mixed $Excerpt 
     * @return void|array{extent: int, position: string, element: array{name: string, text: string, attributes: array{href: string}}} 
     */
    protected function inlineUrl($Excerpt)
    {
        $Inline = DynamicParent::inlineUrl($Excerpt);

        $url = $Inline['element']['attributes']['href'] ?? null;
        if ($url != null) {
            $target = '_self';

            if ($this->allLinksInNewWindow || ($this->extLinksInNewWindow && str_starts_with($url, 'http'))) {
                $target = '_blank';
            }
            $Inline['element']['attributes']['target'] = $target;
        }
        return $Inline;
    }

    /**
     * DigitalSorceress override of inlineUrlTag to add target attribute as needed 
     *
     * I'm overriding the inlinLink, applying my simple logic to it: if there is 
     * an href and allLinksInNewWindow is enabled OR if it starts with 'http' 
     * (which nicely covers both http and https) AND my extLinksInNewWindow is 
     * enabled, then I set target to _blank. All other targets are set to 
     * _self. 
     * 
     * Only done if href found - no target needed for anchor tags without href 
     * 
     * @param mixed $Excerpt 
     * @return void|array{extent: int, element: array{name: string, text: string, attributes: array{href: string}}} 
     */
    protected function inlineUrlTag($Excerpt)
    {
        $tagArray = DynamicParent::inlineUrlTag($Excerpt);

        $url = $tagArray['element']['attributes']['href'] ?? null;
        if ($url != null) {
            $target = '_self';

            ## BEGIN added by DigitalSorceress https://github.com/DigitalSorceress/parsedown
            if ($this->allLinksInNewWindow || ($this->extLinksInNewWindow && str_starts_with($url, 'http'))) {
                $target = '_blank';
            }
            $tagArray['element']['attributes']['target'] = $target;
        }
        return $tagArray;
    }
}



?>
