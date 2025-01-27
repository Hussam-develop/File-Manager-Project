<!--=================================
 header start-->
<nav class="admin-header navbar navbar-default col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <!-- logo -->
    <div class="text-left navbar-brand-wrapper">
        <a class="navbar-brand brand-logo" href="index.html"><img
                src="{{asset('assets/images/culture-center-logo2.png')}}" width="80px" height="200px" alt=""></a>
        <a class="navbar-brand brand-logo-mini" href="index.html"><img src="{{asset('assets/images/User.jpg')}}"
                alt=""></a>
    </div>
    <!-- Top bar left -->
    <ul class="nav navbar-nav mr-auto">
        <li class="nav-item">
            <a id="button-toggle" class="button-toggle-nav inline-block ml-20 pull-left" href="javascript:void(0);"><i
                    class="zmdi zmdi-menu ti-align-right"></i></a>
        </li>
        <li class="nav-item">
            <div class="search">
                <a class="search-btn not_click" href="javascript:void(0);"></a>
                <div class="search-box not-click">
                    <input type="text" class="not-click form-control" placeholder="Search" value="" name="search">
                    <button class="search-button" type="submit"> <i class="fa fa-search not-click"></i></button>
                </div>
            </div>
        </li>
    </ul>

    <!-- top bar right -->
    <ul class="nav navbar-nav ml-auto">
        <li class="nav-item fullscreen">
            <a id="btnFullscreen" href="#" class="nav-link"><i class="ti-fullscreen"></i></a>
        </li>


        <!-- start:language -->
        <li class="nav-item ">
            <a id="my-dropdown" href="#" class="btn btn-secondary btn-sm dropdown-toggle nav-item"
                data-toggle="dropdown">{{ LaravelLocalization::getCurrentLocaleName() }}</a>

            <ul class="dropdown-menu ">
                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                <li class="nav-item">
                    <a rel="alternate" hreflang="{{ $localeCode }}"
                        href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">

                        {{ $properties['native'] }}
                    </a>
                </li>
                @endforeach
            </ul>

        </li>

        <!-- end:language -->
        <li class="nav-item dropdown ">
            <a class="nav-link top-nav" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                aria-expanded="false">
                <i class="ti-bell"></i>
                <span class="badge badge-danger notification-status"> </span>
            </a>
            <!-- notifications -->
            <div class="dropdown-menu dropdown-menu-right dropdown-big dropdown-notifications">
                <div class="dropdown-header notifications" style="background-color: blue">
                    <strong style="color: rgb(224, 224, 255)">unread Notification</strong>
                    <span data-count="{{ auth()->user()->unreadNotifications()->count()}}"
                        class="badge badge-pill badge-warning notif-count">{{
                       auth()->user()->unreadNotifications()->count() }}</span>
                </div>
                <div class="new_message">
                    <div class="dropdown-divider"></div>

                    <p class="dropdown-item newMessageBody">
                        <small class="float-right text-muted time">

                        </small>
                    </p>
                </div>
                @foreach (auth()->user()->unreadNotifications()->take(10)->get() as $notification)
                <div class="dropdown-divider"></div>

               {{--  <p class="dropdown-item"> الاستاذ: {{ $notification->data['userName'] }}
                    <small class="float-right text-muted time">
                        {{ $notification->data['userAction'] }}
                    </small>
                </p> --}}
                <a href="{{ url()->current() }}/?notification_id={{ $notification->id }}" class="dropdown-item"> الاستاذ: {{ $notification->data['userName'] }}
                    <small class="float-right text-muted time">
                        {{ $notification->data['userAction'] }}
                    </small>
                </a>
                @endforeach

                {{-- <a href="#" class="dropdown-item">New registered user <small
                        class="float-right text-muted time">Just now</small> </a>
                --}}
            </div>
        </li>
        <!-- end:notifications -->

        @php
        $type='user'
        @endphp
        @if(auth()->check() && auth()->user()->isAdmin)
        @php
        $type='admin'
        @endphp
        @endif
        <li class="nav-item dropdown mr-30">
            <a class="nav-link nav-pill user-avatar" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                aria-expanded="false">
                @if($type=='admin')
                <img src="{{ asset('assets/images/admin.jpg') }}" alt="avatar">
                @else
                <img src="{{ asset('assets/images/User.jpg') }}" alt="avatar">

                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-header">
                    <div class="media">
                        <div class="media-body">


                            <h5 class="mt-0 mb-0">{{auth()->user()->name }}</h5>
                            <span>{{ auth()->user()->email }}</span>
                        </div>
                    </div>
                </div>
                <div class="dropdown-divider"></div>
                {{-- <a class="dropdown-item" href="#"><i class="text-secondary ti-reload"></i>Activity</a>
                <a class="dropdown-item" href="#"><i class="text-success ti-email"></i>Messages</a>
                <a class="dropdown-item" href="#"><i class="text-warning ti-user"></i>Profile</a>
                <a class="dropdown-item" href="#"><i class="text-dark ti-layers-alt"></i>Projects <span
                        class="badge badge-info">6</span> </a> --}}
                <div class="dropdown-divider"></div>

                <a class="dropdown-item" href="#"><i class="text-info ti-settings"></i>Settings</a>
                {{-- <a href="logout"
                    onclick="event.preventDefault(); document.getElementById('logout').submit();">Logout</a>
                --}}
                <form action="{{ route('logout') }}" method="POST" id="logout">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="type" value="{{ $type }}" />

                </form>
                <button type="submit" class="dropdown-item" form="logout"><i
                        class="text-danger ti-unlock"></i>sign-out</button>
            </div>

        </li>
    </ul>

    {{-- <ul class="dropdown">
        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
        <li>
            <a rel="alternate" hreflang="{{ $localeCode }}"
                href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                {{ $properties['native'] }}
            </a>
        </li>
        @endforeach
    </ul> --}}
</nav>

<!--=================================
 header End-->
<script src="{{ URL::asset('assets/js/jquery-3.3.1.min.js') }}"></script>

<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
    var notificationsWrapper = $('.dropdown-notifications');
            var notificationsCountElem = notificationsWrapper.find('span[data-count]');
            var notificationsCount = parseInt(notificationsCountElem.data('count'));
            var notifications = notificationsWrapper.find('.newMessageBody');
            var newnotifications = notificationsWrapper.find('.new_message');
            newnotifications.hide();
            // if (notificationsCount <= 0) {
            //  notificationsWrapper.hide();
            // }
            // Enable pusher logging - don't include this in production
            Pusher.logToConsole = true;
            var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
                    cluster: '{{ env('PUSHER_APP_CLUSTER') }}'
                     });
                     //
                var groupIds = {!! json_encode(Auth::user()->groups->pluck('id')) !!}; // الحصول على معرفات المجموعات للمستخدم
                 groupIds.forEach(function(groupId) {
                var channel = pusher.subscribe('group-notifications.' + groupId);

               // var channel = pusher.subscribe('groupNotifications');

            channel.bind('App\\Events\\NewActionEvent', function(data) {
                var newNotificationHtml = ` <p class="dropdown-item"> المستخدم: ` + data.userName + `<small
                                class="float-right text-muted time">` + data.userAction + `</small></p>`

        newnotifications.show();
        notifications.html(newNotificationHtml);

                notificationsCount += 1;
                notificationsCountElem.attr('data-count', notificationsCount);
                notificationsWrapper.find('.notif-count').text(notificationsCount);
                notificationsWrapper.show();
            });
        });
</script>
