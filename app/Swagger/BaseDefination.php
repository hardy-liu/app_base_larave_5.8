<?php

/**
 * @OA\OpenApi(
 *     openapi="3.0.0",
 *
 *     @OA\Server(
 *         url=L5_SWAGGER_CONST_HOST,
 *         description="Development Server"
 *     ),
 *
 *     @OA\Info(
 *         version="1.0.0",
 *         title="model market api",
 *         description="modle market api documentation"
 *     ),
 *
 *     @OA\Components(
 *         @OA\Schema(
 *             schema="Success",
 *             description="Success Response",
 *             type="object",
 *             @OA\Property(
 *                 property="success",
 *                 description="执行结果",
 *                 type="boolean",
 *                 example="true",
 *             ),
 *             @OA\Property(
 *                 property="code",
 *                 description="返回码，成功为-1",
 *                 type="integer",
 *                 format="int32",
 *                 example="-1",
 *             ),
 *             @OA\Property(
 *                 property="message",
 *                 description="消息",
 *                 type="string",
 *                 example="操作成功",
 *             ),
 *             @OA\Property(
 *                 property="data",
 *                 description="数据(可能为空)",
 *                 type="object",
 *             ),
 *         ),
 *
 *         @OA\Schema(
 *             schema="ValidationError",
 *             description="验证器错误",
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 description="参数验证失败提示消息",
 *                 type="string",
 *             ),
 *             @OA\Property(
 *                 property="errors",
 *                 description="错误详情",
 *                 type="object",
 *                 ref="#/components/schemas/ValidationErrorDetails",
 *             ),
 *         ),
 *         @OA\Schema(
 *             schema="ValidationErrorDetails",
 *             description="验证器错误详情，key为验证失败的参数名, 值为所有验证失败的条目(数组)",
 *             type="object",
 *             @OA\Property(
 *                 property="stock_code",
 *                 type="array",
 *                 @OA\Items(
 *                     type="string",
 *                     description="参数验证失败详情",
 *                     example={"stock_code 不能大于 1 个字符", "stock_code 应该为数字"},
 *                 ),
 *             ),
 *         ),
 *
 *         @OA\Schema(
 *             schema="CreatedAtUpdatedAt",
 *             type="object",
 *             @OA\Property(
 *                 property="created_at",
 *                 description="创建时间",
 *                 type="string",
 *                 example="2018-03-30 16:03:14",
 *             ),
 *             @OA\Property(
 *                 property="updated_at",
 *                 description="更新时间",
 *                 type="string",
 *                 example="2018-03-30 17:14:42",
 *             ),
 *         ),
 *
 *         @OA\Schema(
 *             schema="Unauthorized",
 *             description="未登录时返回信息",
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 description="消息",
 *                 type="string",
 *                 example="Unauthorized.",
 *             ),
 *         ),
 *     ),
 * )
 */
