<?php
namespace Tests;
use App\User;

class TestUsers
{
  protected $admin_user;
  protected $region_user;
  protected $test_user;
  protected $password = 'testpass123';

  public function getPassword()
  {
    return $this->password;
  }

  public function getAdminUser()
  {
    if (!$this->admin_user) {
      $this->admin_user = User::factory()->create([
        'id' => '100000',
        'name' => 'admin',
        'user_old' => 'admin',
        'email' => 'admin@gmail.com',
        'email_verified_at' => now(),
        'approved_at' => now(),
        'region' => 'HBVDA',
        'password' => bcrypt($this->password),
        'admin' => true,
        'regionadmin' => false
      ]);

      return $this->admin_user;
    }
  }

  public function getRegionUser()
  {
    if (!$this->region_user) {
      $this->region_user = User::factory()->create([
        'id' => '100001',
        'name' => 'region',
        'user_old' => 'admin',
        'email' => 'region@gmail.com',
        'email_verified_at' => now(),
        'approved_at' => now(),
        'region' => 'HBVDA',
        'password' => bcrypt($this->password),
        'admin' => false,
        'regionadmin' => true
      ]);
    }

    return $this->region_user;
  }

  public function getTestUser()
  {
    if (!$this->test_user) {
      $this->test_user = User::factory()->create([
        'id' => '100002',
        'name' => 'testuser',
        'user_old' => 'admin',
        'email' => 'testuser@gmail.com',
        'email_verified_at' => now(),
        'approved_at' => now(),
        'region' => 'HBVDA',
        'password' => bcrypt($this->password),
        'admin' => false,
        'regionadmin' => false
      ]);
    }

    return $this->test_user;
  }

}

?>
