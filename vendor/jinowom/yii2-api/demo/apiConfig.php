<?php


return [    
    'v1'=>[		    
        'user'=>'用户',
        'user.get'=>['class'=>'jinowom\demo\v1\user\Get','auth'=>true], 

        'user.xcx.decode'=>'jinowom\demo\v1\user\Decode',        
        'user.login'=>\jinowom\demo\v1\user\Login::class,
        'user.test'=>\jinowom\demo\v1\user\Test::class,  
        'user.upload'=>\jinowom\demo\v1\user\Upload::class,  
        'user.upload2'=>\jinowom\demo\v1\user\Upload2::class,  
        'user.upload3'=>\jinowom\demo\v1\user\Upload3::class,  
        'user.upload4'=>\jinowom\demo\v1\user\Upload4::class,  

        'cart'=>'购物车',
        'cart.get'      =>\jinowom\demo\v1\cart\Get::class,
        'cart.delete'   =>\jinowom\demo\v1\cart\Delete::class,
        'cart.update'   =>\jinowom\demo\v1\cart\Update::class,
        'cart.checkout'   =>\jinowom\demo\v1\cart\Checkout::class,

        'cart2'=>'购物车2',
        'cart2.get'      =>\jinowom\demo\v1\cart\Get::class,
        'cart2.delete'   =>\jinowom\demo\v1\cart\Delete::class,
        'cart2.update'   =>\jinowom\demo\v1\cart\Update::class,
        'cart2.checkout'   =>\jinowom\demo\v1\cart\Checkout::class,

        'order'=>'订单',
        'theme'=>'主题',
        'member'=>'会员',
        'aftersale'=>'售后',
        'shop'=>'店铺管理',
        'shopmember'=>'店铺用户',
        
    ],
    'v2'=>[		   
        'user'=>'用户',     
        'user.get'=>['class'=>'jinowom\demo\v2\user\Get','auth'=>true],  
    ],

 
    
 
];