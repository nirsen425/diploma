<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .bd-navbar {
            height: 68px;
            background-color: #232a30 !important;
        }

        main {
            position: absolute;
            top: 68px;
            right: 0;
            width: 100%;
            transition: all 0.3s ease-in-out;
        }

        main.toggler {
            width: calc(100% - 250px);
        }

        aside {
            position: fixed;
            top: 68px;
            bottom: 0;
            left: 0;
            width: 250px;
            margin-left: -250px;
            z-index: 9999;
            transition: margin-left 0.3s ease-in-out, top 0s ease-in-out 0.3s;
        }

        aside.toggler {
            margin-left: 0;
        }

        #hideSidebar {
            width: 35px;
            height: 35px;
            line-height: 35px;
            text-align: center;
            background: #e3342f;
            /*background: #2774ac;*/
            position: absolute;
            top: 5px;
            right: 10px;
            z-index: 9999;
            opacity: 0;
            cursor: pointer;
        }

        @media screen and (max-width: 768px) {
            aside.toggler {
                margin-left: -250px;
                top: 68px;
                transition: margin-left 0.3s ease-in-out, top 0s ease-in-out 0.3s;
            }

            aside {
                margin-left: 0;
                top: 0;
                transition: top 0s ease-in-out 0.3s, margin-left 0.3s ease-in-out 0.3s;
            }


            #overlay {
                position: fixed;
                width: 100vw;
                height: 100vh;
                background: rgba(0, 0, 0, 0.7);
                z-index: 9998;
            }

            #overlay.toggler {
                display: none;
            }

            main {
                width: 100%;
            }

            main.toggler {
                width: 100%;
            }

            #hideSidebar {
                opacity: 1;
                transition: opacity 0s ease-in-out 0.3s;
            }
        }

        /*Dropdown style*/
        a[data-toggle="collapse"] {
            position: relative;
        }

        .dropdown-toggle::after {
            display: block;
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
        }
        /*End Dropdown style*/

        /*Sidebar style*/
        p {
            font-family: 'Poppins', sans-serif;
            font-size: 1.1em;
            font-weight: 300;
            line-height: 1.7em;
            color: #999;
        }

        a, a:hover, a:focus {
            color: inherit;
            text-decoration: none;
            transition: all 0.3s;
        }

        aside {
            /* don't forget to add all the previously mentioned styles here too */
            background: #7386D5;
            color: #fff;
        }

        aside .sidebar-header {
            padding: 20px;
            background: #32373d;
            position: relative;
        }

        aside ul.components {
            padding: 20px 0;
        }

        aside ul p {
            color: #fff;
            padding: 10px;
        }

        aside ul li a {
            padding: 10px;
            font-size: 1.1em;
            display: block;
        }
        aside ul li a:hover {
            background-color: #e3342f;
        }

        aside ul li.active > a, a[aria-expanded="true"] {
            color: #fff;
            /*background: #2774ac;*/
            background-color: #e3342f;
        }
        ul ul a {
            font-size: 0.9em !important;
            padding-left: 30px !important;
            background: #32373b;
        }

        .collapse.navbar-collapse {
            justify-content: flex-end;
        }
        /*End Sidebar style*/
    </style>
    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
</head>
<body>
<nav class="navbar navbar-expand fixed-top bd-navbar navbar-dark bg-primary">
    <button type="button" id="switchSidebar" class="btn btn-danger">
        <i class="fas fa-align-left"></i>
        <span>Переключить</span>
    </button>
    <div class="collapse navbar-collapse" id="navbarColor02">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Features</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Pricing</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">About</a>
            </li>
        </ul>
    </div>
</nav>

<aside class="toggler bg-dark">
    <div id="hideSidebar" class="d-md-none">
        <i class="fas fa-arrow-left"></i>
    </div>
    <div class="sidebar-header">
        <h3>Sidebar</h3>
    </div>

    <ul class="list-unstyled components">
        <p>Dummy Heading</p>
        <li class="active">
            <a href="#teachersSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Преподаватели</a>
            <ul class="collapse list-unstyled" id="teachersSubmenu">
                <li>
                    <a href="#">Просмотреть</a>
                </li>
                <li>
                    <a href="#">Добавить</a>
                </li>
                <li>
                    <a href="#">Редактировать</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#studentsSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Студенты</a>
            <ul class="collapse list-unstyled" id="studentsSubmenu">
                <li>
                    <a href="#">Просмотреть</a>
                </li>
                <li>
                    <a href="#">Добавить</a>
                </li>
                <li>
                    <a href="#">Pедактировать</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#newsSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Новости</a>
            <ul class="collapse list-unstyled" id="newsSubmenu">
                <li>
                    <a href="#">Просмотреть</a>
                </li>
                <li>
                    <a href="#">Добавить</a>
                </li>
                <li>
                    <a href="#">Pедактировать</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#">Contact</a>
        </li>
    </ul>
</aside>

<main class="toggler p-5">
    <div class="jumbotron">
        @yield('content')
    </div>
</main>

<div id="overlay" class="toggler"></div>
<script>
    $(document).ready(function () {
        $('#overlay').on('click', function () {
            $('aside').toggleClass('toggler');
            $('#overlay').toggleClass('toggler');
            $('main').toggleClass('toggler');
        });

        $('#switchSidebar, #hideSidebar').on('click', function () {
            $('aside').toggleClass('toggler');
            $('#overlay').toggleClass('toggler');
            $('main').toggleClass('toggler');
        });
    });
</script>
</body>