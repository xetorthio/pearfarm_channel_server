<?php
	/**	
		* @package functional_test	
		*/
	require_once('nimblize/nimble_test/lib/phpunit_testcase.php');
  class UserControllerTest extends NimbleFunctionalTestCase {
		
		public function setUp() {
      $_SERVER['SERVER_NAME'] = 'bob.localhost';
      $this->user = User::find_by_username('bob');
		}
		
		public function testUserUpdateEmail() {
		  $this->put('update', array(), array('id' => $this->user->id, 'user' => array('email' => 'my_new_email@email')), array('user' => $this->user->id));
		  $user = User::_find($this->user->id);
		  $this->assertEquals($this->user->password, $user->password);
		  $this->assertEquals('my_new_email@email', $user->email);
		  $this->assertRedirect('/');
		}
		
		public function testUserUpdatePassword() {
		  $this->put('update', array(), array('id' => $this->user->id, 'v_password' => 'mypassword',  'user' => array('email' => 'my_new_email@email', 'password' => 'mypassword')), array('user' => $this->user->id));
		  $user = User::_find($this->user->id);
		  $this->assertNotEquals($this->user->password, $user->password);
		  $this->assertEquals('my_new_email@email', $user->email);
		  $this->assertRedirect('/');
		}
		
		public function testDeleteUser() {
		  $count = User::count();
		  $this->delete('delete', array(), array('id' => $this->user->id), array('user' => $this->user->id));
      $this->assertEquals($count - 1, User::count(array('cache' => false)));
      $this->assertRedirect('http://' . DOMAIN);
		}
		
		public function testAddKey() {
		  $count = Pki::count();
		  $this->post('add_key', array(), array('pki' => array('name' => 'my_key', 'key' => md5(time()))), array('user' => $this->user->id));
      $this->assertEquals($count + 1, Pki::count(array('cache' => false)));
      $this->assertEquals($this->response, 'true');
		}
		
		public function testAddKeyFails() {
		  $count = Pki::count();
		  $this->post('add_key', array(), array('pki' => array('key' => md5(time()))), array('user' => $this->user->id));
      $this->assertEquals($count, Pki::count(array('cache' => false)));
      $this->assertEquals($this->response, 'false');
		}
		
		public function testUpdateKey() {
		  $key = $this->user->pkis->first();
		  $count = Pki::count();
		  $this->post('update_key', array(), array('id' => $key->id, 'pki' => array('name' => 'my_key', 'key' => md5(time()))), array('user' => $this->user->id));
      $this->assertEquals($count, Pki::count(array('cache' => false)));
      $this->assertEquals($this->response, 'true');
      $key2 = Pki::_find($key->id);
      $this->assertEquals($key2->name, 'my_key');
      $this->assertNotEquals($key->key, $key2->key);
		}
		
		public function testUpdateKeyFails() {
		  $key = $this->user->pkis->first();
		  $count = Pki::count();
		  $this->post('update_key', array(), array('id' => $key->id, 'pki' => array('name' => '', 'key' => md5(time()))), array('user' => $this->user->id));
      $this->assertEquals($count, Pki::count(array('cache' => false)));
      $this->assertEquals($this->response, 'false');
      $key2 = Pki::_find($key->id);
      $this->assertEquals($key2->name, $key->name);
      $this->assertEquals($key->key, $key2->key);
		}
		
		public function testDeleteKey() {
		  $key = $this->user->pkis->first();
		  $count = Pki::count();
		  $this->delete('delete_key', array(), array('id' => $key->id), array('user' => $this->user->id));
		  $this->assertEquals($count - 1, Pki::count(array('cache' => false)));
		  $this->assertEquals($this->response, 'true');
		}
		
		public function testDeleteFails() {
		  $key = $this->user->pkis->first();
		  $count = Pki::count();
		  $this->delete('delete_key', array(), array('id' => 568), array('user' => $this->user->id));
		  $this->assertEquals($count, Pki::count(array('cache' => false)));
		  $this->assertEquals($this->response, 'false');
		}
		
		
	}
?>