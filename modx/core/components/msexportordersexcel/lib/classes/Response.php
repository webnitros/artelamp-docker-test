<?php
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class Response extends SymfonyResponse
{
    use ResponseTrait;

    /** @var array $data Data for the chunk */
    public $data = [];
    /**
     * Set the content on the response.
     *
     * @param  mixed  $content
     * @return SymfonyResponse
     */
    public function setContent($content)
    {
        $this->original = $content;
        if ($this->shouldBeJson($content)) {
            $content = $this->ToJson($content);
        } else if ($this->shouldBeExcel($content)) {
            $content = $this->ToExcel($content);
        }
        return parent::setContent($content);
    }
    /**
     * Set data for the chunk.
     *
     * @param  array  $data
     * @return $this
     */
    public function with(array $data = [])
    {
        $this->data = $data;
        return $this;
    }
    /**
     * Determine if the given content should be turned into JSON.
     *
     * @param  mixed  $content
     * @return bool
     */
    protected function shouldBeJson($content)
    {
        return $this->isJsonable($content) ||
            $content instanceof ArrayObject ||
            $content instanceof JsonSerializable ||
            $content instanceof msExportOrdersExcelPHPExcelJSONController ||
            is_array($content);
    }

    /**
     * Determine if the given content should be turned into JSON.
     *
     * @param  mixed  $content
     * @return bool
     */
    protected function shouldBeExcel($content)
    {
        return $content instanceof msExportOrdersExcelPhpExcelExcelController;
    }

    /**
     * Convert the given content into JSON format.
     *
     * @param  mixed   $content
     * @return string
     */
    protected function ToExcel($content)
    {
        if ($this->isExcelable($content)) {
            return $content->toExcel();
        }
        return json_encode($content);
    }

    /**
     * Convert the given content into JSON format.
     *
     * @param  mixed   $content
     * @return string
     */
    protected function ToJson($content)
    {
        if ($this->isJsonable($content)) {
            return $content->toJson();
        }
        return json_encode($content);
    }
    /**
     * Returns the Response as an HTTP string.
     *
     * The string representation of the Response is the same as the
     * one that will be sent to the client only if the prepare() method
     * has been called before.
     *
     * @return string The Response as an HTTP string
     *
     * @see prepare()
     */
    public function __toString()
    {
        $this->processData();

        return parent::__toString();
    }
    /**
     * Sends HTTP headers and content.
     *
     * @return SymfonyResponse
     */
    public function send()
    {
        $this->processData();
        return parent::send();
    }

    protected function processData()
    {
        if (!empty($this->data)) {
            $this->setContent($this->parse($this->getContent(), $this->data));
        }
    }

    /**
     * Parse a string using an associative array of replacement variables.
     *
     * @param string $string Source string to parse.
     * @param array $data An array of placeholders to replace.
     * @param string|bool $prefix Magic. The placeholder prefix or flag for complete parsing.
     * @param string|int $suffix Magic. The placeholder suffix (for simple mode) or
     * the maximum iterations to recursively process tags.
     * @return string The processed string with the replaced placeholders.
     */
    private function parse($string, $data, $prefix = '[[+', $suffix = ']]')
    {
        global $modx;
        if (!empty($string)) {
            if (is_array($data)) {
                if (is_bool($prefix) && $prefix) {
                    $parser = $modx->getParser();
                    $maxIterations = (is_numeric($suffix)) ? (int)$suffix : (int)$modx->getOption('parser_max_iterations', null, 10);
                    $scope = $modx->toPlaceholders($data, '', '.', true);
                    $parser->processElementTags('', $string, false, false, '[[', ']]', array(), $maxIterations);
                    $parser->processElementTags('', $string, true, true, '[[', ']]', array(), $maxIterations);
                    if (isset($scope['keys'])) $modx->unsetPlaceholders($scope['keys']);
                    if (isset($scope['restore'])) $modx->toPlaceholders($scope['restore']);
                } else {
                    foreach ($data as $key => $value) {
                        $string = str_replace($prefix . $key . $suffix, $value, $string);
                    }
                }
            }
        }
        return $string;
    }
}