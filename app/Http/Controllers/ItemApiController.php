<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Item;
use App\Repositories\ItemRepository;

class ItemApiController extends Controller
{
    protected $items;

    public function __construct(ItemRepository $repository)
    {
        $this->items = $repository;
    }

    /**
     * Try/Catch a clousure
     *
     * @param  \Clousure  $function
     * @return \Illuminate\Http\Response
     */
    protected function try_catch($function)
    {
        try {

            return $function();
            
        } catch (Exception $exception) {

            Log::error($exception->getMessage());
            return response()->json(['message' => 'Internal server error'], 500);

        }
    }

    // User single operations

    /**
     * Get all active items
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        return $this->try_catch(            
            function () {

                $items = $this->items->all();
                
                if ($items->isNotEmpty()) {
                    return response()->json(['items' => $items], 200);
                }
    
                return response()->json(['message' => 'No items found'], 404);
    
            }
        );
    }

    /**
     * Find an active item
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function find($id)
    {
        return $this->try_catch(            
            function () use ($id) {
                
                $item = $this->items->find($id);

                if ($item) {
                    return response()->json($item, 200);
                }

                return response()->json(['error' => 'Item not found'], 404);
    
            }
        );
    }

    /**
     * Create an item
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return $this->try_catch(            
            function () use ($request) {
                
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'value' => 'required',
                ]);
    
                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()->all()], 400);
                } else {
                    return response()->json(['message' => 'Created', 'item' => $this->items->create($request->all())], 201);
                }
    
            }
        );
    }

    /**
     * Update an item
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        return $this->try_catch(            
            function () use ($request) {
                
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'value' => 'required',
                ]);

                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()->all()], 400);
                } else {
                    $item = $this->items->find($request->id);

                    if ($item) {
                        return response()->json(['message' => 'Item updated', 'item' => $this->items->update($item, $request->all())], 200);
                    } else {
                        return response()->json(['error' => 'Item not found'], 404);
                    }
                }
            }
        );
    }

    /**
     * Delete an item
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        try {
            throw new Exception("Error Processing Request", 1);

            $item = Item::find($id);

            if($item){
                // $item->delete();
                $message = ['message' => 'Item deleted', 'item' => $item];
                $status_code = 200;
            } else {
                $message = ['message' => 'Item not found'];
                $status_code = 404;
            }

        } catch (Exception $e) {
            
            $message = ['message' => 'Not deleted. Internal server error'];
            // $message = ['message' => $exception->getMessage()];
            $status_code = 500;

        }

        return response()->json($message, $status_code);
    }

    /**
     * Restore an item
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        try {

            $item = Item::withTrashed()->find($id);
            // TODO add authorization (user is owner of the item?)
            
            if($item){
                if($item->trashed()){
                    $item->restore();
                    $message = ['message' => 'Item restored', 'item' => $item];
                    $status_code = 200;
                } else {
                    $message = ['message' => 'Item already restored', 'item' => $item];
                    $status_code = 200;
                }
            } else {
                $message = ['error' => 'Item not found'];
                $status_code = 404;
            }

        } catch (Exception $e) {
            
            $message = ['error' => 'Not deleted. Internal server error'];
            // $message = ['error' => $exception->getMessage()];
            $status_code = 500;

        }

        return response()->json($message, $status_code);

    }

    // User bulk operations

    /**
     * Get a list of active items
     *
     * @return \Illuminate\Http\Response
     */
    public function bulkFind($ids)
    {
        // TODO add validation here
        $exploded_ids = explode(',', $ids);

        $items = [];
        foreach ($exploded_ids as $key => $id) {
            $item = Item::find($id);
            if ($item) {
                $items[$id] = $item;
            }
        }

        if($items){
            return response()->json($items, 200);
        }

        return response()->json(['error' => 'Items not found'], 404);
    }

    /**
     * Create a list of items
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkCreate(Request $request)
    {
        $requested_items = $request->all();

        if ($requested_items) {

            try {

                $created_items = [];
                $failed_items = [];
                DB::beginTransaction();
                
                foreach ($requested_items as $item) {
    
                    $validator = Validator::make($item, [
                        'name' => 'required',
                        'value' => 'required',
                    ]);
    
                    if ($validator->fails()) {
                        $failed_items[] = [ 'item' => $item, 'errors' => $validator->errors()->all()];
                    } else {
                        $created_items[] = Item::create($item);
                    }
                }
    
                if ($failed_items) {
                    DB::rollBack();
                    $message = ['message' => 'Bad request', 'items' => $failed_items];
                    $status_code = 400;
                    return response()->json($message, $status_code);
    
                } else {
    
                    DB::commit();
                    $message = ['message' => 'Items successfully created', 'items' => $created_items];
                    $status_code = 201;
                    return response()->json($message, $status_code);
                    
                }
    
            } catch (Exception $exception) {
    
                DB::rollBack();
                
                $message = ['error' => 'Not created. Internal server error'];
                // $item = ['error' => $exception->getMessage()];
                $status_code = 500;
                return response()->json($message, $status_code);
    
            }

        } else {
            return response()->json(['message' => 'No data has been sent'], 400);
        }
        
    }

    /**
     * Update a list of items
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkUpdate(Request $request)
    {
        $requested_items = $request->all();
        dd($requested_items);

        if ($requested_items) {

            try {

                $updated_items = [];

                /**
                 * continue from here...
                 */
                $failed_items = [];
                DB::beginTransaction();
                
                foreach ($requested_items as $item) {
    
                    $validator = Validator::make($item, [
                        'name' => 'required',
                        'value' => 'required',
                    ]);
    
                    if ($validator->fails()) {
                        $failed_items[] = [ 'item' => $item, 'errors' => $validator->errors()->all()];
                    } else {
                        $created_items[] = Item::create($item);
                    }
                }
    
                if ($failed_items) {
                    DB::rollBack();
                    $message = ['message' => 'Bad request', 'items' => $failed_items];
                    $status_code = 400;
                    return response()->json($message, $status_code);
    
                } else {
    
                    DB::commit();
                    $message = ['message' => 'Items successfully created', 'items' => $created_items];
                    $status_code = 201;
                    return response()->json($message, $status_code);
                    
                }
    
            } catch (Exception $exception) {
    
                DB::rollBack();
                
                $message = ['error' => 'Not created. Internal server error'];
                // $item = ['error' => $exception->getMessage()];
                $status_code = 500;
                return response()->json($message, $status_code);
    
            }

        } else {
            return response()->json(['message' => 'No data has been sent'], 400);
        }
    }

    // Admin single operations

    /**
     * Get all items
     *
     * @return \Illuminate\Http\Response
     */
    public function adminAll()
    {
        $items = Item::withTrashed()->get();

        if($items->isNotEmpty()){
            return response()->json(['items' => $items], 200);
        }

        return response()->json(['message' => 'No items found'], 200);
    }

    /**
     * Find any item
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function adminFind($id)
    {
        $item = Item::withTrashed()->find($id);

        if($item){
            return response()->json($item, 200);
        }

        return response()->json(['error' => 'Item not found'], 404);
    }

    /**
     * Create any item
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function adminCreate(Request $request)
    // {
    //     try {

    //         $validator = Validator::make($request->all(), [
    //             'name' => 'required',
    //             'value' => 'required',
    //         ]);

    //         if ($validator->fails()) {
    //             $item = ['errors' => $validator->errors()];
    //             $status_code = 400;
    //         } else {
    //             $item = Item::create($request->all());
    //             $status_code = 201;
    //         }

    //     } catch (Exception $exception) {
            
    //         $item = ['error' => 'Not created. Internal server error'];
    //         // $item = ['error' => $exception->getMessage()];
    //         $status_code = 500;

    //     }

    //     return response()->json($item, $status_code);
    // }

    /**
     * Update any item
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function adminUpdate(Request $request, $id)
    {
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'value' => 'required',
            ]);

            if ($validator->fails()) {
                $message = ['errors' => $validator->errors()];
                $status_code = 400;
            } else {
                $item = Item::withTrashed()->find($id);

                if($item){
                    $item->update($request->all());
                    $message = ['message' => 'Item updated', 'item' => $item];
                    $status_code = 200;
                } else {
                    $message = ['error' => 'Item not found'];
                    $status_code = 404;
                }
            }

        } catch (Exception $e) {
            
            $message = ['error' => 'Not updated. Internal server error'];
            // $message = ['error' => $exception->getMessage()];
            $status_code = 500;

        }

        return response()->json($message, $status_code);

    }

    /**
     * Delete any item
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function adminDelete($id)
    {
        try {

            $item = Item::withTrashed()->find($id);

            if($item){
                if($item->trashed()){
                    $message = ['message' => 'Item already deleted', 'item' => $item];
                    $status_code = 200;
                } else {
                    $item->delete();
                    $message = ['message' => 'Item deleted', 'item' => $item];
                    $status_code = 200;
                }
            } else {
                $message = ['error' => 'Item not found'];
                $status_code = 404;
            }

        } catch (Exception $e) {
            
            $message = ['error' => 'Not deleted. Internal server error'];
            // $message = ['error' => $exception->getMessage()];
            $status_code = 500;

        }

        return response()->json($message, $status_code);
    }

    /**
     * Restore any item
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function adminRestore($id)
    {
        try {

            $item = Item::withTrashed()->find($id);
            
            if($item){
                if($item->trashed()){
                    $item->restore();
                    $message = ['message' => 'Item restored', 'item' => $item];
                    $status_code = 200;
                } else {
                    $message = ['message' => 'Item already restored', 'item' => $item];
                    $status_code = 200;
                }
            } else {
                $message = ['error' => 'Item not found'];
                $status_code = 404;
            }

        } catch (Exception $e) {
            
            $message = ['error' => 'Not deleted. Internal server error'];
            // $message = ['error' => $exception->getMessage()];
            $status_code = 500;

        }

        return response()->json($message, $status_code);

    }

    /**
     * Deletar todos os registros
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteAll()
    {
        try {
            
            $items = Item::withTrashed()->get();
            dd($items);
            
            if($items){
                foreach ($items as $item) {
                    $item->delete();
                }                
                return $items;
            }
            
            return [];

        } catch (Exception $e) {
            
            return [];

        }
    }    
}
