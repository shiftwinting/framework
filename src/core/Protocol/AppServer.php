<?php
namespace SPF\Protocol;
use SPF;

class AppServerException extends \Exception
{

}

class AppServer extends HttpServer
{
    protected $router_function;
    protected $apps_path;

    function onStart($serv, $worker_id = 0)
    {
        parent::onStart($serv, $worker_id);
        if (empty($this->apps_path))
        {
            if (!empty($this->config['apps']['apps_path']))
            {
                $this->apps_path = $this->config['apps']['apps_path'];
            }
            else
            {
                throw new AppServerException("AppServer require apps_path");
            }
        }
        $php = App::getInstance();
        $php->addHook(Swoole::HOOK_CLEAN, function(){
            $php = App::getInstance();
            //模板初始化
            if (!empty($php->tpl))
            {
                $php->tpl->clear_all_assign();
            }
        });
    }

    /**
     * 处理请求
     * @param SPF\Request $request
     * @return SPF\Response
     */
    function onRequest(SPF\Request $request)
    {
        return App::getInstance()->handlerServer($request);
    }
}
