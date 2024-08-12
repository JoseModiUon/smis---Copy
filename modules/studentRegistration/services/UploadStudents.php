<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 1/16/2024
 * @time: 11:53 AM
 */
namespace app\modules\studentRegistration\services;

abstract class UploadStudents
{
    /**
     * @param array $file excel template with students
     * @return array duplicate students if present
     */
    abstract protected function save(array $file): array;
}