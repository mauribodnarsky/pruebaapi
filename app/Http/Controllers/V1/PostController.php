<?php
namespace App\Http\Controllers\V1;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
/**
 * @OA\Info(
 *      version="1.0.0", 
 *      title="Syloper - Desafio Backend",
 *      description=" Este proyecto desarrollado en laravel 8 contiene un endpoint para autenticar usuarios a través de JWT. Además posee una API Rest de ABM de Posts",
 * )
 */
class PostController extends Controller
{
    protected $user;
    public function __construct(Request $request)
    {
        $token = $request->header('Authorization');
        if($token != '')
            //En caso de que requiera autentifiación la ruta obtenemos el usuario y lo almacenamos en una variable, nosotros no lo utilizaremos.
            $this->user = JWTAuth::parseToken()->authenticate();
    }


    /**
 * Display a listing of the resource.
 * Mostramos el listado de los regitros solicitados.
 * @return \Illuminate\Http\Response
 *
 * @OA\Get(
 *     path="/api/v1/posts",
 *     tags={"posts"},
 *     summary="Mostrar el listado de posts",
 *     @OA\Response(
 *         response=200,
 *         description="Mostrar todas los posts."
 *     ),
 *     @OA\Response(
 *         response="default",
 *         description="Ha ocurrido un error."
 *     )
 * ) 
 */
    public function index()
    {
        //Listamos todos los posts
        return Post::get();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */














    /** @OA\Post(
    *     path="/api/posts",
    *     tags={"users"},
    *     summary="Registrar nuevo post",
     *    @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="titulo",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="slug",
     *                          type="string"
     *                      )
     * ,
     *                      @OA\Property(
     *                          property="imagen",
     *                          type="string"
     *                      ),
     * 
     *                      @OA\Property(
     *                          property="descripcion",
     *                          type="string"
     *                      )
     *                 )
     *             )
     *         )
     *      ),
    *     @OA\Response(
    *         response=200,
    *         description="Usuario creado."
    *     ),
    *     @OA\Response(
    *         response=400,
    *         description="Error ."
    *     ),
    
    *  
    * ) 
    */
    


    public function store(Request $request)
    {
        //Validamos los datos
        $data = $request->only('titulo','slug', 'imagen', 'descripcion');
        $validator = Validator::make($data, [
            'titulo' => 'required|max:100|string',
            'slug' => 'required|max:100|string',            
            'imagen' => 'required|max:100|string',
            'descripcion' => 'required|max:100|string',
        ]);
        //Si falla la validación
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        
        //Creamos el post en la BD
        $post = Post::create([
            'titulo' => $request->titulo,
            'slug' => $request->slug,            
            'imagen' => $request->imagen,
            'descripcion' => $request->descripcion,
        ]);
        //Respuesta en caso de que todo vaya bien.
        return response()->json([
            'message' => 'Post creado',
            'data' => $post
        ], Response::HTTP_OK);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ost  $post
     * @return \Illuminate\Http\Response
     */
    /**
     
     * Display the specified resource.
    * Muestra el registro solicitado
    * @param  string  $slug
    * @return \Illuminate\Http\Response
    * @OA\Post(
    *     path="/api/v1/posts/{slug}",
    *     tags={"posts"},
    *     summary="Mostrar info de un post",
    *     @OA\Parameter(
    *         description="Parámetro necesario para la consulta de datos de un post",
    *         in="path",
    *         name="slug",
    *         required=true,
    *         @OA\Schema(type="string")),
        *     @OA\Parameter(
    *         description="token jwt necesario para la consulta de datos de un post",
    *         in="header",
    *         name="token",
    *         required=true,
    *         @OA\Schema(type="string")),
    *     @OA\Response(
    *         response=200,
    *         description="Mostrar info de un post."
    *     ),
    *     @OA\Response(
    *         response=404,
    *         description="Post no existe."
    *     ),
    
    *     @OA\Response(
    *         response=505,
    *         description="Token has expired ."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * ) 
    */
    public function show($slug)
    {
        //Bucamos el post
        $post = Post::find()->where('slug',$slug);
        //Si el post no existe devolvemos error no encontrado
        if (!$post) {
            return response()->json([
                'message' => 'Post no existe.'
            ], 404);
        }
        //Si hay producto lo devolvemos
        return $post;
    }







    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */

    /** @OA\Put(
        *     path="/api/v1/posts/{id}",
        *     tags={"posts"},
        *     summary="Modificar de un post",
        *     @OA\Parameter(
        *         description="id del post a modificar",
        *         in="path",
        *         name="id",
        *         required=true,
        *         @OA\Schema(type="string")),
        *     @OA\Parameter(
        *         description="token jwt necesario para la modificacion de datos de un post",
        *         in="header",
        *         name="token",
        *         required=true,
        *         @OA\Schema(type="string")),
     *         @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="titulo",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="slug",
     *                          type="string"
     *                      )
     * ,
     *                      @OA\Property(
     *                          property="descripcion",
     *                          type="string"
     *                      ),
     * 
     *                      @OA\Property(
     *                          property="imagen",
     *                          type="string"
     *                      )
     *                 )
     *             )
     *         )
     *      ),
        *     @OA\Response(
        *         response=400,
        *         description="Error."
        *     ),
        
        *     @OA\Response(
        *         response=401,
        *         description="Authorization Token not found."
        *     ),
        
        *     @OA\Response(
        *         response=505,
        *         description="Token has expired ."
        *     ),
        *     @OA\Response(
        *         response="200",
        *         description="Post modificado correctamente."
        *     )
        * ) 
        */
        

    public function update(Request $request, $id)
    {
        //Validación de datos
        $data = $request->only('titulo', 'descripcion', 'imagen');
        $validator = Validator::make($data, [
            'titulo' => 'required|max:100|string',
            'descripcion' => 'required|max:100|string',
            'imagen' => 'required|max:1000|string',
        ]);
        //Si falla la validación error.
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        //Buscamos el producto
        $post = Post::findOrfail($id);
        //Actualizamos el producto.
        $post->update([
            'titulo' => 'required|max:100|string',
            'slug' => 'required|max:100|string',            
            'descripcion' => 'required|max:100|string',
            'imagen' => 'required|max:1000|string',
       ]);
        //Devolvemos los datos actualizados.
        return response()->json([
            'message' => 'post modificado correctamente',
            'data' => $post
        ], Response::HTTP_OK);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $product
     * @return \Illuminate\Http\Response
     */



    /** @OA\Delete(
        *     path="/api/posts/{id}",
        *     tags={"posts"},
        *     summary="Eliminar  un post",
        *     @OA\Parameter(
        *        
        *         description="token jwt necesario para la eliminacion de datos de un post",
        *         in="header",
        *         name="token",
        *         required=true,
        *         @OA\Schema(type="string")),
        *     @OA\Parameter(

        *         description="Parámetro necesario para la eliminacion de un post",
        *         in="path",
        *         name="id",
        *         required=true,
        *         @OA\Schema(type="string"),
        
        *     ),
        *     @OA\Response(
        *         response=404,
        *         description="Error."
        *     ),
        
        *     @OA\Response(
        *         response=401,
        *         description="Authorization Token not found."
        *     ),
        
        *     @OA\Response(
        *         response=505,
        *         description="Token has expired ."
        *     ),
        *     @OA\Response(
        *         response="200",
        *         description="post borrado correctamente."
        *     )
        * ) 
        */
        




    public function destroy($id)
    {
        //Buscamos el producto
        $post = Post::findOrfail($id);
        //Eliminamos el posto
        $post->delete();
        //Devolvemos la respuesta
        return response()->json([
            'message' => 'post borrado correctamente'
        ], Response::HTTP_OK);
    }
}