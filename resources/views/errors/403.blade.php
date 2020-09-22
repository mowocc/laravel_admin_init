<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@lang('globe.site_name')</title>
    <style>
        body {
            margin: 0;
            font-family: "Helvetica Neue", Helvetica, "PingFang SC", "Hiragino Sans GB", "Microsoft YaHei", "\5FAE\8F6F\96C5\9ED1", Arial, sans-serif;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #606266;
            text-align: left;
            background-color: #FFFFFF;
        }

        .flex-center {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            display: flex;
            flex-direction: column;
            height: calc(100vh);
            width: calc(100vw);
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 2rem;
            padding: 20px;
        }

    </style>
</head>
<body>
<div class="container flex-center">
    <div class="content">
        <div class="title">
            @lang('view.error.403')
        </div>
    </div>
</div>
</body>
</html>
