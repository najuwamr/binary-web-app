<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;
use RuntimeException;

class RichTextSanitizer
{
    private const ALLOWED_TAGS = ['p', 'div', 'br', 'strong', 'b', 'em', 'i', 'u', 'ol', 'ul', 'li'];

    private const DROP_CONTENT_TAGS = ['script', 'style', 'iframe', 'object', 'svg', 'math', 'template'];

    public function sanitize(string $html): string
    {
        if ($html === '') {
            return '';
        }

        $document = new DOMDocument('1.0', 'UTF-8');
        $previousErrorMode = libxml_use_internal_errors(true);

        try {
            $loaded = $document->loadHTML(
                '<?xml encoding="UTF-8"><div id="rich-text-root">'.$html.'</div>',
                LIBXML_HTML_NODEFDTD,
            );
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previousErrorMode);
        }

        if (! $loaded) {
            throw new RuntimeException('Rich text content could not be parsed.');
        }

        $root = $document->getElementsByTagName('div')->item(0);

        if (! $root instanceof DOMElement) {
            throw new RuntimeException('Rich text root element is missing.');
        }

        return $this->sanitizeChildren($root);
    }

    private function sanitizeChildren(DOMNode $node): string
    {
        $output = '';

        foreach ($node->childNodes as $child) {
            if ($child instanceof DOMText) {
                $output .= htmlspecialchars($child->nodeValue ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

                continue;
            }

            if (! $child instanceof DOMElement) {
                continue;
            }

            $tag = strtolower($child->tagName);

            if (in_array($tag, self::DROP_CONTENT_TAGS, true)) {
                continue;
            }

            $contents = $this->sanitizeChildren($child);

            if (! in_array($tag, self::ALLOWED_TAGS, true)) {
                $output .= $contents;

                continue;
            }

            $output .= $tag === 'br'
                ? '<br>'
                : '<'.$tag.'>'.$contents.'</'.$tag.'>';
        }

        return $output;
    }
}
