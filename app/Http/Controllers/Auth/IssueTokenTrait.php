<?php 
namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
trait IssueTokenTrait{
	public function issueToken(Request $request, $grantType, $scope = ""){
		
		$params = [
    		'grant_type' => $grantType,
    		'client_id' => '1',
    		'client_secret' => 'iIlhjBD5cVJN3z9Etpafty9vQTJdEXTSSV3XyhaE',    		
    		'scope' => $scope
    	];
        if($grantType !== 'social'){
            $params['username'] = $request->username ?: $request->email;
        }
    	$request->request->add($params);
    	$proxy = Request::create('oauth/token', 'POST');
    	return Route::dispatch($proxy);
	}
}