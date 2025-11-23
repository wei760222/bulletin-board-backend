<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="布告欄 API",
 *      description="This is the API for the Bulletin Board project.",
 *      @OA\Contact(
 *          email="admin@example.com"
 *      )
 * )
 * @OA\SecurityScheme(
 *    securityScheme="sanctum",
 *    type="http",
 *    scheme="bearer",
 *    bearerFormat="JWT",
 * )
 */
abstract class Controller
{
    //
}
