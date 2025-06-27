<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class EncryptedPassword extends Model
{
    protected $fillable = [
        'first_password',
        'second_password',
    ];

    /**
     * Get the decrypted first password
     */
    public function getFirstPasswordDecrypted()
    {
        return Crypt::decrypt($this->first_password);
    }

    /**
     * Get the decrypted second password
     */
    public function getSecondPasswordDecrypted()
    {
        return Crypt::decrypt($this->second_password);
    }

    /**
     * Verify if the provided password matches the first password
     */
    public function verifyFirstPassword($password)
    {
        return $this->getFirstPasswordDecrypted() === $password;
    }

    /**
     * Verify if the provided password matches the second password
     */
    public function verifySecondPassword($password)
    {
        return $this->getSecondPasswordDecrypted() === $password;
    }
} 