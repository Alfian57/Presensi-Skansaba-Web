<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="Presensi Skansaba API",
 *     version="1.0.0",
 *     description="API Documentation for Presensi Skansaba - Student Attendance System",
 *
 *     @OA\Contact(
 *         email="admin@skansaba.com",
 *         name="Presensi Skansaba Support"
 *     )
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter your Bearer token in the format: Bearer {token}"
 * )
 *
 * @OA\Tag(
 *     name="Authentication",
 *     description="Authentication endpoints - login, logout, profile"
 * )
 * @OA\Tag(
 *     name="Profile",
 *     description="User profile management"
 * )
 * @OA\Tag(
 *     name="Attendance",
 *     description="Attendance check-in, check-out, and history"
 * )
 * @OA\Tag(
 *     name="Schedule",
 *     description="Class schedules"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
