<?php
/**
 * 性能分析插件
 * 需要先安装xhprof扩展
 */
class XhprofPlugin extends Yaf\Plugin_Abstract {

    public function routerStartup(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response) {
        //只能在php.ini中指定
        //ini_set('xhprof.output_dir', APPLICATION_PATH . '/data');
        // start profiling
//        xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
        xhprof_enable();

    }

    public function dispatchLoopShutdown(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response) {
        // stop profiler
        $xhprof_data = xhprof_disable();
        require APPLICATION_PATH . '/xhprof/xhprof_lib/utils/xhprof_lib.php';
        require APPLICATION_PATH . '/xhprof/xhprof_lib/utils/xhprof_runs.php';

        // save raw data for this profiler run using default
        // implementation of iXHProfRuns.
        $xhprof_runs = new XHProfRuns_Default();
        // save the run under a namespace "xhprof_foo"
        $run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_analyze");
        //需要查看调用图片时，需要安装graphviz
        //$html = '<a href="' . XHPROF_HOST . '/xhprof_html/index.php" target="_blank">[xhprof result]</a>';
        //$response->appendBody($html);
    }

}
