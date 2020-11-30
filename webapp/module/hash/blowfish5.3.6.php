<?php

/**
 * blowfish �^����ꂽ������ƃR�X�g���� Blowfish �n�b�V����Ԃ�
 * 
 * @param $raw ���̕�����i�����p�X���[�h�j
 * @param $cost �R�X�g�i4�ȏ�31�ȉ��̐����j
 * @return string �����Ŏw�肵��������� Blowfish �n�b�V��
 */
function blowfish($raw, $cost = 4) {
    // Blowfish�̃\���g�Ɏg�p�ł��镶����
    $chars = array_merge(range('a', 'z'), range('A', 'Z'), array('.', '/'));

    // �\���g�𐶐��i��L�����킩��Ȃ郉���_����22�����j
    $salt = '';
    for ($i = 0; $i < 22; $i++) {
        $salt .= $chars[mt_rand(0, count($chars) - 1)];
    }

    // �R�X�g�̑O����
    $costInt = intval($cost);
    if ($costInt < 4) {
        $costInt = 4;
    } elseif ($costInt > 31) {
        $costInt = 31;
    }

    // �w�肳�ꂽ�R�X�g�� Blowfish �n�b�V���𓾂�
    return crypt($raw, '$2a$' . sprintf('%02d', $costInt) . '$' . $salt);
}

