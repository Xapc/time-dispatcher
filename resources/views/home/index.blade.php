<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <!-- <base href="/"> -->

    <title></title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Template Basic Images Start -->
    <meta property="og:image" content="path/to/image.jpg">
    <link rel="icon" href="img/favicon/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="img/favicon/apple-touch-icon-180x180.png">
    <!-- Template Basic Images End -->

    <!-- Custom Browsers Color Start -->
    <meta name="theme-color" content="#000">
    <!-- Custom Browsers Color End -->

    <link rel="stylesheet" href="{{asset('/site/css/main.min.css?v1')}}">


</head>

<body>

<!-- Custom HTML -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="/">Время работы сотрудников</a>
</nav>
<section class="section-data">
    <form>
        <div class="container-fluid">
            <div class="row">
                <div class="form-group col-6">
                    <label for=formControlSelect1>Выберите месяц</label>
                    <select type="month" class="form-control" id="formControlSelect1" name="month">
                        <option value="1">Месяц 1</option>
                        <option value="2">Месяц 2</option>
                        <option value="3">Месяц 3</option>
                        <option value="4">Месяц 4</option>
                        <option value="5">Месяц 5</option>
                        <option value="6">Месяц 6</option>
                        <option value="7">Месяц 7</option>
                        <option value="8">Месяц 8</option>
                        <option value="9">Месяц 9</option>
                        <option value="10">Месяц 10</option>
                        <option value="11">Месяц 11</option>
                        <option value="12">Месяц 12</option>
                    </select>
                </div>
                <div class="form-group col-6">
                    <label for="formControlSelect2">Список сотрудников</label>
                    <select multiple class="form-control" id="formControlSelect2" name="accountId">
                        @foreach($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </form>

    <div class="container-fluid table-scrollable">

        <div class="row">

            <div class="col-12" id="table">
            </div>

        </div>

        <div class="btn-group btn-export" role="group">
            <a href="/" id="button" class="btn btn-primary" >Скачать отчет</a>
        </div>

    </div>
</section>

<script src="{{asset('/site/js/scripts.min.js?v1')}}"></script>
<script src="{{asset('/site/js/common.js?v1')}}"></script>
</body>
</html>
