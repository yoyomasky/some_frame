<?php
/**
 * @author: somewhere
 * @since: 2021/6/25
 * @version: 1.0
 */
namespace core;
class Response{
    protected $headers = [];//要发送的请求头
    protected $content = ''; // 要发送的内容
    protected $code = 200; // 发送状态码

    /**
     * 发送内容
     */
    public function sendContent()
    {
        echo $this->content;
    }

    /**
     * 发送请求头
     */
    public function sendHeaders()
    {
        foreach ($this->headers as $key => $header)
            header($key.': '.$header);

    }

    /**
     * 发送
     * @return $this
     */
    public function send()
    {
        $this->sendHeaders();
        $this->sendContent();
        return $this;
    }

    /**
     * 设置内容
     * @param $content
     * @return $this
     */
    public function setContent($content)
    {

        if( is_array($content)){
            $content = json_encode($content);
        }
        $this->content = $content;
        return $this;
    }

    /**
     * 获取内容
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * 获取状态码
     * @return int
     */
    public function getStatusCode()
    {
        return $this->code;
    }

    /**
     * 设置状态码
     * @param int $code
     * @return $this
     */
    public function setCode(int $code)
    {
        $this->code = $code;
        return $this;
    }
}