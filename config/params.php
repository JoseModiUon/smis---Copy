<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 10/06/2024
 * @time: 11:59 AM
 */

return [
    'bsVersion' => '5.x', // Kartik Global Bootstrap Config
    'sysName' => 'SMIS',
    'orgName' => 'NDU-K',
    'sitename' => 'smis',
    'sitenameLong' => 'smis',

    /**
     * Email accounts
     */
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'supportEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',

    /**
     * Password reset url
     */
    'resetPasswordUrl' => 'http://usermgtdev.uonbi.ac.ke/site/reset-password',

    'gender' => ['M' => 'MALE', 'F' => 'FEMALE'],
    'bloodGrp' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-',],
    'image' => [
        'default' => ['width' => 150, 'height' => 180,]
    ],

    /**
     * Academic years
     */
    'currentAcademicYear' => '2023/2024',
    'academicYears' => [
        '2024/2025',
        '2023/2024',
        '2022/2023',
        '2021/2022',
        '2020/2021',
        '2019/2020',
        '2018/2019',
        '2017/2018',
        '2016/2017',
        '2015/2016'
    ]
];
