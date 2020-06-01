<?php
namespace Benyazi\TelegramLog;

class Logger
{
    const DEFAULT_ENDPOINT = 'https://api.telegram.org';
    protected $endpoint;
    protected $defaultHashTags = [];
    protected $defaultData = [];
    protected $defaultChatId;
    private $botKey;

    /**
     * Logger constructor.
     * @param string $botKey
     * @param string $chatId
     * @param string|null $endpoint
     * @param array $defaultHashTags
     * @param array $defaultData
     */
    public function __construct($botKey, $chatId, $endpoint = null, $defaultHashTags = [], $defaultData = [])
    {
        $this->defaultChatId = $chatId;
        $this->botKey = $botKey;
        $this->defaultHashTags = $defaultHashTags;
        $this->defaultData = $defaultData;
        if ($endpoint) {
            $this->endpoint = trim($endpoint,'/');
        } else {
            $this->endpoint = self::DEFAULT_ENDPOINT;
        }
    }

    /**
     * Get bot endpoint
     * @return string
     */
    protected function getEndpoint()
    {
        return $this->endpoint . '/bot' . $this->botKey;
    }

    /**
     * Send message to telegram
     * @param string $text
     * @return string
     */
    private function sendMessage($text)
    {
        $params = [
            'chat_id' => $this->defaultChatId,
            'text' => $text,
        ];
        $ch = curl_init($this->getEndpoint() . '/sendMessage');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        try {
            $result = curl_exec($ch);
            curl_close($ch);
        } catch (\Exception $e) {

        }
        return true;
    }

    /**
     * Convert hashtags array to formatted string
     * @param $hashtags
     * @return string
     */
    private function getHashtagString($hashtags)
    {
        $formatted_hashtags = [];
        foreach ($hashtags as $hashtag) {
            if(mb_strpos($hashtag, '#') !== 0) {
                $hashtag = '#' . $hashtag;
            }
            $formatted_hashtags[] = $hashtag;
        }
        return join(' ', $formatted_hashtags);
    }

    /**
     * Convert data array to formatted string
     * @param $data
     * @return string
     */
    private function getDataString($data)
    {
        return '```' . print_r($data, true) . '```';
    }

    /**
     * Push info log message
     * @param string $msg
     * @param array $data
     * @param array $hashtags
     * @return bool|string
     */
    public function info($msg, $data = [], $hashtags = [])
    {
        if(!empty($data) || !empty($this->defaultData)) {
            $data = array_merge($data, $this->defaultData);
            $msg .= PHP_EOL . PHP_EOL . $this->getDataString($data);
        }
        $hashtags[] = 'info';
        $hashtags = array_merge($hashtags, $this->defaultHashTags);
        $msg .= PHP_EOL . PHP_EOL . $this->getHashtagString($hashtags);
        return $this->sendMessage($msg);
    }

    /**
     * Push error log message
     * @param string $msg
     * @param array $data
     * @param array $hashtags
     * @return bool|string
     */
    public function error($msg, $data = [], $hashtags = [])
    {
        if(!empty($data) || !empty($this->defaultData)) {
            $data = array_merge($data, $this->defaultData);
            $msg .= PHP_EOL . PHP_EOL . $this->getDataString($data);
        }
        $hashtags[] = 'error';
        $hashtags = array_merge($hashtags, $this->defaultHashTags);
        $msg .= PHP_EOL . PHP_EOL . $this->getHashtagString($hashtags);
        return $this->sendMessage($msg);
    }
}