<?php

namespace App\Helpers;

class SidebarHelper {
  static $menu = [
    'dashboard' => [
      'title' => 'Dashboard',
      'url' => 'admin.index',
      'icon' => 'fas fa-tachometer-alt',
      'permission' => 'dashboard'
    ],
    'ticket' => [
      'title' => 'Vé xe',
      'url' => 'admin.ticket.index',
      'icon' => 'fas fa-ticket-alt',
      'permission' => 'xem-ve'
    ],
    'schedule' => [
      'title' => 'Lịch chạy',
      'url' => 'admin.trip_date.index',
      'icon' => 'fas fa-clipboard-list',
      'permission' => 'xem-lich-chay'
    ],
    'trip' => [
      'title' => 'Chuyến',
      'url' => 'admin.trip.index',
      'icon' => 'fas fa-shuttle-van',
      'permission' => 'xem-chuyen'
    ],
    'route' => [
      'title' => 'Tuyến đường',
      'url' => 'admin.route.index',
      'icon' => 'fas fa-road',
      'permission' => 'xem-tuyen-duong'
    ],
    'place' => [
      'title' => 'Địa diểm',
      'url' => 'admin.place.index',
      'icon' => 'fas fa-map-marker-alt',
      'permission' => 'xem-dia-diem'
    ],
    'district' => [
      'title' => 'Quận huyện',
      'url' => 'admin.district.index',
      'icon' => 'fas fa-map-signs',
      'permission' => 'xoa-khu-vuc'
    ],
    'province' => [
      'title' => 'Tỉnh thành',
      'url' => 'admin.province.index',
      'icon' => 'fas fa-city',
      'permission' => 'xem-tinh'
    ],
    'brand' => [
      'title' => 'Nhà xe',
      'url' => 'admin.brand.index',
      'icon' => 'fas fa-bus',
      'permission' => 'xem-hang-xe'
    ],
    'user' => [
      'title' => 'Người dùng và phân quyền',
      'url' => 'admin.user.index',
      'icon' => 'fas fa-users',
      'permission' => 'xem-nguoi-dung'
    ],
    'report' => [
      'title' => 'Thống kê',
      'url' => 'admin.report.index',
      'icon' => 'fas fa-table',
      'permission' => 'xem-chuyen'
    ]
  ];

  public static function getSideBar()
  {
      $user = auth('admin')->user();

      $userPermissions = $user->role->permissions()->pluck('slug')->toArray();
      $menu = self::$menu;
      $showMenu = [];
      foreach ($menu as $key => $value) {
        if (in_array($value['permission'], $userPermissions) || $value['permission'] == 'dashboard') {
          $showMenu[] = $value;
        }
      }
      return $showMenu;
  }
}
