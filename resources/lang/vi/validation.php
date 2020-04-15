<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':Attribute phải được chấp nhận.',
    'active_url' => ':Attribute không phải là một URL.',
    'after' => ':Attribute phải là một ngày sau ngày :date.',
    'after_or_equal' => ':Attribute phải là một ngày sau đó hoặc bằng với :date.',
    'alpha' => ':Attribute phải chứa các ký tự',
    'alpha_dash' => ':Attribute chỉ có thể chứa chữ cái, số, dấu gạch ngang và dấu gạch dưới.',
    'alpha_num' => ':Attribute chỉ có thể chứa chữ cái và số.',
    'array' => ':Attribute phải là một mảng.',
    'before' => ':Attribute phải là một ngày trước ngày :date.',
    'before_or_equal' => ':Attribute phải là một ngày trước đó hoặc bằng với :date.',
    'between' => [
        'numeric' => ':Attribute phải nằm trong khoảng :min đến :max.',
        'file' => ':Attribute có kích thước nằm trong :min đến :max kilobytes.',
        'string' => ':Attribute có độ dài từ :min đến :max ký tự.',
        'array' => ':Attribute phải chứa :min đến :max phần tử.',
    ],
    'boolean' => 'Trường :attribute phải là đúng hoặc sai.',
    'confirmed' => ':Attribute không giống nhau.',
    'date' => ':Attribute không đúng định dàng ngày tháng.',
    'date_format' => ':Attribute không đúng định dạng :format.',
    'different' => ':Attribute và :other phải khác nhau.',
    'digits' => ':Attribute phải là :digits chữ số.',
    'digits_between' => ':Attribute phải chứa :min đến :max chữ số.',
    'dimensions' => ':Attribute có kích thước hình ảnh không hợp lệ.',
    'distinct' => 'Trường :attribute có giá trị bị lặp lại.',
    'email' => 'Trường :attribute phải là một địa chỉ email.',
    'exists' => ':Attribute được chọn không khả dụng.',
    'file' => ':Attribute phải là một file.',
    'filled' => 'Trường :attribute không được để trống.',
    'gt' => [
        'numeric' => ':Attribute phải có giá trị lớn hơn :value.',
        'file' => ':Attribute phải có kích thước lớn hơn :value kilobytes.',
        'string' => ':Attribute phải dài hơn :value ký tự.',
        'array' => ':Attribute phải chứa nhiều hơn :value phần tử.',
    ],
    'gte' => [
        'numeric' => ':Attribute phải có giá trị lớn hơn hoặc bằng :value.',
        'file' => ':Attribute phải có kích thước lớn hơn hoặc bằng :value kilobytes.',
        'string' => ':Attribute phải chứa từ :value ký tự.',
        'array' => ':Attribute phải chứa nhiều hoặc hơn :value phần tử.',
    ],
    'image' => ':Attribute phải là hình ảnh.',
    'in' => ':Attribute được chọn không khả dụng.',
    'in_array' => 'Trường :attribute không tồn tại trong :other.',
    'integer' => ':Attribute phải là một số nguyên.',
    'ip' => ':Attribute phải là một địa chỉ IP khả dụng.',
    'ipv4' => ':Attribute phải là một địa chỉ IPv4 khả dụng.',
    'ipv6' => ':Attribute phải là một địa chỉ IPv6 khả dụng.',
    'json' => ':Attribute phải là một chuỗi JSON.',
    'lt' => [
        'numeric' => ':Attribute phải nhỏ hơn :value.',
        'file' => ':Attribute phải nhỏ hơn :value kilobytes.',
        'string' => ':Attribute phải nhỏ hơn :value ký tự.',
        'array' => ':Attribute chứa ít hơn :value phần tử.',
    ],
    'lte' => [
        'numeric' => ':Attribute phải nhỏ hơn hoặc bằng :value.',
        'file' => ':Attribute phải nhỏ hơn hoặc bằng :value kilobytes.',
        'string' => ':Attribute phải nhỏ hơn hoặc bằng :value ký tự.',
        'array' => ':Attribute không thể chứa nhiều hơn :value phần tử.',
    ],
    'max' => [
        'numeric' => ':Attribute không thể lớn hơn :max.',
        'file' => ':Attribute không thể lớn hơn :max kilobytes.',
        'string' => ':Attribute không thể lớn hơn :max ký tự.',
        'array' => ':Attribute không thể chứa nhiều hơn :max phần tử.',
    ],
    'mimes' => ':Attribute phải thuộc một trong các định dạng: :values.',
    'mimetypes' => ':Attribute phải thuộc một trong các định dạng: :values.',
    'min' => [
        'numeric' => ':Attribute tối thiểu là :min.',
        'file' => ':Attribute tối thiểu là :min kilobytes.',
        'string' => ':Attribute tối thiểu là :min ký tự.',
        'array' => ':Attribute tối thiểu là :min phần tử.',
    ],
    'not_in' => ':Attribute được chọn không khả dụng.',
    'not_regex' => ':Attribute không đúng định dạng.',
    'numeric' => ':Attribute phải là một số.',
    'present' => 'Trường :attribute phải được hiển thị.',
    'regex' => ':Attribute không đúng định dạng.',
    'required' => 'Trường :attribute không được bỏ trống.',
    'required_if' => 'Trường :attribute là bắt buộc khi :other là :value.',
    'required_unless' => 'Trường :attribute là bắt buộc trừ khi :other là :values.',
    'required_with' => 'Trường :attribute là bắt buộc khi :values được hiển thị.',
    'required_with_all' => 'Trường :attribute là bắt buộc khi tất cả :values được hiển thị.',
    'required_without' => 'Trường :attribute là bắt buộc khi :values không được hiển thị.',
    'required_without_all' => 'Trường :attribute là bắt buộc khi không có :values nào được hiển thị.',
    'same' => 'Trường :attribute và :other phải giống nhau.',
    'size' => [
        'numeric' => 'Trường :attribute có giá trị là :size.',
        'file' => 'Trường :attribute có kích thước là :size kilobytes.',
        'string' => 'Trường :attribute phải chứa :size ký tự.',
        'array' => 'Trường :attribute phải chứa :size phần tử.',
    ],
    'string' => 'Trường :attribute phải là một chuỗi.',
    'timezone' => 'Trường :attribute phải là một múi giờ.',
    'unique' => ':Attribute đã tồn tại.',
    'uploaded' => ':Attribute không thể tải lên.',
    'url' => ':Attribute không đúng định dạng.',
    'max_size' => ':Attribute có kích thước không được quá 2Mb.',
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'oops' => 'Ôii...',
        'invalidImage' => 'Ảnh không đúng định dạng, phải là .jpg, .jpeg hoặc .png',
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'title' => 'Tiêu đề',
        'description' => 'Mô tả',
        'author' => 'Tác giả',
        'categories' => 'Danh mục',
        'publish_date' => 'Ngày đăng',
        'bio' => 'Giới thiệu',
        'content' => 'Nội dung',
        'password' => 'Mật khẩu',
        'phone' => 'Số điện thoại',
        'employee_code' => 'Mã nhân viên',
        'reputation_point' => 'Điểm danh tiếng',
        'avatar' => 'Ảnh đại diện',
        'password_confirmation' => 'Mật khẩu xác nhận',
        'name' => 'Tên',
        'cover' => 'Ảnh bìa',
    ],

];
