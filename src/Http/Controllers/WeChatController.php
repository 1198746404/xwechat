<?php
namespace Xx\Wechat\Http\Controllers;

use Illuminate\Http\Request;
use Xx\Wechat\Http\Controllers\Controller;

class WeChatController extends Controller
{
    public function hello()
    {
        var_dump('hello world');
    }
    public function index(Request $request)
    {
        // return 1;
        $signature = $request->input('signature');
        $timestamp = $request->input('timestamp');
        $nonce = $request->input('nonce');
        // 手动新增的参数
        // 只有在第一次对接的时候才会存在
        // 因此可以根据这个参数来判断是否之前校验过
        $echostr = $request->input('echostr');
        // 加密过程
        $token = "xam";
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);$tmpStr = implode( $tmpArr );$tmpStr = sha1( $tmpStr );
        if( $tmpStr == $signature ){
          // 额外修改的代码
          if (empty($echostr)) { // 是否有关联
              // 回复信息
              // 接收微信发送的参数
              $postObj =file_get_contents('php://input');
              $postArr = simplexml_load_string($postObj,"SimpleXMLElement",LIBXML_NOCDATA);

              //消息内容
              $content = $postArr->Content;
              //接受者
              $toUserName = $postArr->ToUserName;
              //发送者
              $fromUserName = $postArr->FromUserName;
              //获取时间戳
              $time = time();
              //你好，你的消息是： $content
              $content = "我也 $content";
              //把百分号（%）符号替换成一个作为参数进行传递的变量：
              $info = sprintf('<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[text]]></MsgType>
                <Content><![CDATA[%s]]></Content>
              </xml>', $fromUserName, $toUserName, $time, $content);
              return $info;
          } else {
              return $echostr;
          }
        }else{
           return "false";
        }
    }
}
