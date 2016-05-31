1.ThinkerCMS必须安装在根目录，否则会出现文件css、js等无法加载的情况。

2.ThinkerCMS访问路径采用的pathinfo模式，所以在apache下必须开启rewirite模式，在nginx下必须进行相应的配置。可参手册http://document.thinkphp.cn/manual_3_2.html#url_rewrite

3.安装：访问 网址+Install即可完成安装，若想从新安装需删除/Runtime/install.lock

4.在apache下开启rewirite时路径依然不对可尝试在根目录中的.htaccess文件中的index.php后添加问号。

5.遇到其他报错可尝试百度一下或在论坛的有问必答栏目中提问。

6.欢迎加入QQ群：463751265