<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 4/29/2018
 * Time: 10:04 AM
 */

namespace App;


class OperationMessage
{

    //////////////Codes/////////////
    //public
    const OperationNotDefinedCode= -1;
    const OperationErrorCode= 0;
    const OperationFailCode = 1;
    const OperationSuccessCode = 2;
    const OperationUnauthorizedCode = 3;

    ////////////////////////////////
    const UserNotAllowed = 4;
    ///////////////////////////////
    const DeadLineReached = 5;
    const ItemNotFind = 6;
    const ChangeDenied = 7;

    const NotEnoughBalance = 8;
    const AlreadyExist = 9;
    const InsufficientStorage = 10;
    const InsufficientLink = 11;
    const NotValidDateFormat=12;

    ////user related message///
    const EmailAlreadyExist = 13;
    const LicenseNotRead = 14;
    const WrongCaptcha = 15;
    ////////////////////////////




    const RedMessages = [-1,0,1,3,4,5,6,7,8,9,10,11,13,14,15];
    const GreenMessages = [2];


    public function initialize()
    {
        $this->Code = null;
        $this->Text = null;
    }

    public function initializeByCode($code)
    {
        $this->Code = $code;
        switch ($this->Code)
        {
            case self::OperationErrorCode:
                $this->Text =  trans('messages.txt_OperationError');
                break;
            case self::OperationFailCode:
                $this->Text =  trans('messages.txt_OperationFail');
                break;
            case self::OperationSuccessCode:
                $this->Text =  trans('messages.txt_OperationSuccess');
                break;
            case self::UserNotAllowed:
                $this->Text =  trans('messages.txt_UserNotAllowed');
                break;
            case self::DeadLineReached:
                $this->Text =  trans('messages.txt_DeadLineReached');
                break;
            case self::ItemNotFind:
                $this->Text =  trans('messages.txt_ItemNotFind');
                break;
            case self::ChangeDenied:
                $this->Text =  trans('messages.txt_ChangeDenied');
                break;
            case self::NotEnoughBalance:
                $this->Text =  trans('messages.txt_NotEnoughBalance');
                break;
            case self::AlreadyExist:
                $this->Text =  trans('messages.txt_AlreadyExist');
                break;
            case self::OperationUnauthorizedCode:
                $this->Text =  trans('messages.txt_OperationUnauthorized ');
                break;
            case self::InsufficientStorage:
                $this->Text =  trans('messages.txt_InsufficientStorage ');
                break;
            case self::InsufficientLink:
                $this->Text =  trans('messages.txt_InsufficientLink ');
                break;
            case self::NotValidDateFormat:
                $this->Text =  trans('messages.txt_NotValidDateFormat');
                break;
            case self::EmailAlreadyExist:
                $this->Text =  trans('messages.msgErrorEmailExist');
                break;
            case self::LicenseNotRead:
                $this->Text =  trans('messages.msgErrorLicenseNotRead');
                break;
            case self::WrongCaptcha:
                $this->Text =  trans('messages.msgErrorWrongCaptcha');
                break;
            default:
                $this->Text =  trans('messages.txtOperationNotDefined');
                break;
        }
    }

    public function getMessage($code = null)
    {
        if($code != null)
        {
            $this->Code = $code;
        }

        switch ($this->Code)
        {
            case self::OperationErrorCode:
                return trans('messages.txt_OperationError');
            case self::OperationFailCode:
                return trans('messages.txt_OperationFail');
            case self::OperationSuccessCode:
                return trans('messages.txt_OperationSuccess');
            case self::OperationUnauthorizedCode:
                return trans('messages.txt_OperationUnauthorized');
            case self::UserNotAllowed:
                return trans('messages.txt_UserNotAllowed');
            case self::DeadLineReached:
                return trans('messages.txt_DeadLineReached');
            case self::ItemNotFind:
                return trans('messages.txt_ItemNotFind');
            case self::ChangeDenied:
                return trans('messages.txt_ChangeDenied');
            case self::NotEnoughBalance:
                return trans('messages.txt_NotEnoughBalance');
            case self::AlreadyExist:
                return trans('messages.txtAlreadyExist');
            case self::OperationUnauthorizedCode:
                return trans('messages.txtOperationUnauthorized ');
            case self::InsufficientStorage:
                return trans('messages.txtInsufficientStorage ');
            case self::InsufficientLink:
                return trans('messages.txtInsufficientLink ');
            case self::NotValidDateFormat:
                return trans('messages.txtNotValidDateFormat');
            case self::EmailAlreadyExist:
                return trans('messages.msgErrorEmailExist');
            case self::LicenseNotRead:
                return trans('messages.msgErrorLicenseNotRead');
            case self::WrongCaptcha:
                return trans('messages.msgErrorWrongCaptcha');

            default:
                return trans('message.txtOperationNotDefined');
        }
    }
}