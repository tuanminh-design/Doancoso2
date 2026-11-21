<?php
namespace App\Controllers;

use App\Helpers\Session;
use App\Helpers\Url;
use App\Helpers\Validator;
use App\Models\ContactMessage;

class PageController extends Controller
{
    private ContactMessage $contactModel;

    public function __construct()
    {
        parent::__construct();
        $this->contactModel = new ContactMessage();
    }

    public function contact(): void
    {
        $settings = $this->settingModel->getAll();

        $this->render('pages.contact', [
            'pageTitle' => 'Liên Hệ & Địa Chỉ Cửa Hàng - DaNang PC',
            'settings'  => $settings,
        ]);
    }

    public function submitContact(): void
    {
        $this->validateCsrf();

        $validator = Validator::make($_POST);
        $validator->required('name', 'Họ tên là bắt buộc.')
                  ->required('email', 'Email là bắt buộc.')
                  ->email('email', 'Email không hợp lệ.')
                  ->required('subject', 'Tiêu đề là bắt buộc.')
                  ->required('message', 'Nội dung tin nhắn không được để trống.');

        if ($validator->fails()) {
            Session::setFlash('error', implode('<br>', $validator->errors()));
            Url::redirect('contact');
            return;
        }

        $this->contactModel->create([
            'name'    => trim($_POST['name']),
            'email'   => trim($_POST['email']),
            'phone'   => !empty($_POST['phone']) ? trim($_POST['phone']) : null,
            'subject' => trim($_POST['subject']),
            'message' => trim($_POST['message']),
        ]);

        Session::setFlash('success', 'Cảm ơn bạn đã gửi tin nhắn liên hệ! Đội ngũ tư vấn DaNang PC sẽ phản hồi sớm nhất qua email hoặc điện thoại.');
        Url::redirect('contact');
    }

    public function policy(string $type): void
    {
        $validTypes = [
            'shipping' => ['title' => 'Chính Sách Vận Chuyển & Giao Hàng', 'key' => 'policy_shipping'],
            'warranty' => ['title' => 'Chính Sách Bảo Hành Chính Hãng',     'key' => 'policy_warranty'],
            'return'   => ['title' => 'Chính Sách Đổi Trả & Hoàn Tiền',    'key' => 'policy_return'],
            'privacy'  => ['title' => 'Chính Sách Bảo Mật Thông Tin',      'key' => 'policy_privacy'],
        ];

        if (!isset($validTypes[$type])) {
            http_response_code(404);
            $this->render('pages.404', ['pageTitle' => 'Trang không tồn tại']);
            return;
        }

        $info = $validTypes[$type];
        $content = $this->settingModel->get($info['key'], '<p>Nội dung đang được cập nhật.</p>');

        $this->render('pages.policy', [
            'pageTitle'   => $info['title'] . ' - DaNang PC',
            'policyTitle' => $info['title'],
            'content'     => $content,
            'activeType'  => $type,
        ]);
    }
}
