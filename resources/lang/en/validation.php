<?php

return [
    /*
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */
    'accepted'             => 'The :attribute phải được chấp nhận.',
    'active_url'           => 'The :attribute không phải là một URL hợp lệ.',
    'after'                => 'The :attribute phải là một ngày sau: date.',
    'after_or_equal'       => 'The :attribute phải là ngày sau hoặc bằng: date.',
    'alpha'                => 'The :attribute chỉ có thể chứa chữ cái.',
    'alpha_dash'           => 'The :attribute chỉ có thể chứa chữ cái, số và dấu gạch ngang.',
    'alpha_num'            => 'The :attribute chỉ có thể chứa chữ cái và số.',
    'array'                => 'The :attribute phải là một mảng.',
    'before'               => 'The :attribute phải là ngày trước: date.',
    'before_or_equal'      => 'The :attribute phải là ngày trước hoặc bằng: date.',
    'between'              => [
        'numeric' => 'The :attribute phải nằm trong khoảng: min và: max.',
        'file'    => 'The :attribute phải nằm trong khoảng :min và :max kilobytes.',
        'string'  => 'The :attribute phải nằm trong khoảng :min và :max kí tự.',
        'array'   => 'The :attribute phải có từ :min và :max items.',
    ],
    'boolean'              => 'The :attribute phải là đúng hoặc sai.',
    'confirmed'            => 'Xác nhận attribute không khớp.',
    'date'                 => 'The :attribute không phải là ngày hợp lệ.',
    'date_format'          => 'The :attribute không khớp với định dạng: format.',
    'different'            => 'The :attribute và :other là phải khác nhau.',
    'digits'               => 'The :attribute phải là: các chữ số chữ số.',
    'digits_between'       => 'The :attribute phải nằm giữa: min và: max chữ số.',
    'dimensions'           => 'The :attribute có kích thước hình ảnh không hợp lệ.',
    'distinct'             => 'The :attribute có một giá trị trùng lặp.',
    'email'                => 'The :attribute phải là một địa chỉ email hợp lệ.',
    'exists'               => 'The :attribute được chọn: không hợp lệ.',
    'file'                 => 'The :attribute phải là tệp.',
    'filled'               => 'The :attribute phải có một giá trị.',
    'image'                => 'The :attribute phải là một hình ảnh.',
    'in'                   => 'The :attribute được chọn không hợp lệ.',
    'in_array'             => 'The :attribute không tồn tại trong: other.',
    'integer'              => 'The :attribute phải là một số nguyên.',
    'ip'                   => 'The :attribute phải là một địa chỉ IP hợp lệ.',
    'ipv4'                 => 'The :attribute phải là địa chỉ IPv4 hợp lệ.',
    'ipv6'                 => 'The :attribute phải là địa chỉ IPv6 hợp lệ.',
    'json'                 => 'The :attribute phải là một chuỗi JSON hợp lệ.',
    'max'                  => [
        'numeric' => 'The :attribute không được lớn hơn: max.',
        'file'    => 'The :attribute không được lớn hơn :max kilobytes.',
        'string'  => 'The :attribute không được lớn hơn :max characters.',
        'array'   => 'The :attribute không có nhiều hơn :max items.',
    ],
    'mimes'                => 'The :attribute phải là tệp kiểu:: values.',
    'mimetypes'            => 'The :attribute phải là tệp kiểu:: values.',
    'min'                  => [
        'numeric' => 'The :attribute phải ít nhất: min.',
        'file'    => 'The :attribute phải ít nhất: min kilobytes.',
        'string'  => 'The :attribute phải có ít nhất: min ký tự.',
        'array'   => 'The :attribute phải có ít nhất: min items.',
    ],
    'not_in'               => 'Thuộc tính được chọn: không hợp lệ.',
    'numeric'              => 'Thuộc tính: phải là một số.',
    'present'              => 'The :attribute field must be present.',
    'regex'                => 'Định dạng The :attribute không hợp lệ.',
    'required'             => 'The :attribute là bắt buộc.',
    'required_if'          => 'The :attribute được yêu cầu khi :other is :value.',
    'required_unless'      => 'The :attribute field is required unless :other is in :values.',
    'required_with'        => 'The :attribute được yêu cầu khi :giá trị hiện có.',
    'required_with_all'    => 'The :attribute được yêu cầu khi :giá trị hiện có.',
    'required_without'     => 'The :attribute được yêu cầu khi :giá trị không có sẵn.',
    'required_without_all' => 'The :attribute được yêu cầu khi none of :giá trị hiện có.',
    'same'                 => 'The :attribute và :other phải khớp.',
    'size'                 => [
        'numeric' => 'The :attribute phải là: size.',
        'file'    => 'The :attribute phải là :size kilobytes.',
        'string'  => 'The :attribute phải là :size characters.',
        'array'   => 'The :attribute phải chứa các mục :size items.',
    ],
    'string'               => 'The :attribute phải là một chuỗi.',
    'timezone'             => 'The :attribute phải là một vùng hợp lệ.',
    'unique'               => 'The :attribute đã được dùng.',
    'uploaded'             => 'Không thể tải lên The :attribute.',
    'url'                  => 'Định dạng The :attribute không hợp lệ.',

    /*
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
