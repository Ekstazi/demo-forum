<?php
namespace app\components\helpers;

class Html
{
    public static function openTag($tagName, $options = [])
    {
        return '<' . $tagName . ' ' . self::renderOptions($options) . '>';
    }

    protected static function renderOptions($options)
    {
        $parts = [];
        foreach ($options as $name => $value) {
            if (is_bool($value)) {
                $value = $value ? 1 : 0;
            }
            $parts[] = $name . '="' . htmlspecialchars($value) . '"';
        }
        return implode(' ', $parts);
    }

    public static function closeTag($tagName)
    {
        return "</{$tagName}>";
    }

    public static function tag($tagName, $content, $options = [])
    {
        return self::openTag($tagName, $options) . $content . self::closeTag($tagName);
    }

    public static function a($title, $url, $options = [])
    {
        $options['href'] = Url::to($url);
        return self::tag('a', $title, $options);
    }
}