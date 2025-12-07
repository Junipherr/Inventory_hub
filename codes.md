# Custodian Inventory System Codes

This document contains the relevant code snippets for the Custodian Inventory System, organized by functionality. Each section includes the code with added comments for clarity.
```php
<?php
// routes/web.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\BorrowController;

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::resource('inventory', InventoryController::class);

Route::post('inventory/import', [InventoryController::class, 'importCsv'])->name('inventory.import');
Route::get('inventory/export', [InventoryController::class, 'exportCsv'])->name('inventory.export');

Route::post('borrow/{id}', [BorrowController::class, 'borrow'])->name('borrow');
Route::post('return/{id}', [BorrowController::class, 'return'])->name('return');

<?php
// app/Http/Controllers/AuthController.php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {
    public function showLoginForm() { return view('login'); }

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->route('inventory.index');
        }
        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('login');
    }
}

<?php
// app/Http/Controllers/InventoryController.php
namespace App\Http\Controllers;
use App\Models\Inventory;
use Illuminate\Http\Request;
use League\Csv\Reader;
use League\Csv\Writer;

class InventoryController extends Controller {

    public function index() { return Inventory::all(); }

    public function store(Request $request) { return Inventory::create($request->all()); }

    public function show($id) { return Inventory::findOrFail($id); }

    public function update(Request $request, $id) {
        $item = Inventory::findOrFail($id);
        $item->update($request->all());
        return $item;
    }

    public function destroy($id) { Inventory::destroy($id); }

    // CSV Import
    public function importCsv(Request $request) {
        $path = $request->file('csv')->getRealPath();
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0);
        foreach ($csv as $row) { Inventory::create($row); }
        return "CSV Imported Successfully";
    }

    // CSV Export
    public function exportCsv() {
        $csv = Writer::createFromString('');
        $csv->insertOne(['name','quantity','status']);
        foreach(Inventory::all() as $item) {
            $csv->insertOne([$item->name,$item->quantity,$item->status]);
        }
        return response($csv->getContent(), 200, ['Content-Type' => 'text/csv']);
    }
}

<?php
// app/Http/Controllers/BorrowController.php
namespace App\Http\Controllers;
use App\Models\Inventory;

class BorrowController extends Controller {
    public function borrow($id) {
        $item = Inventory::findOrFail($id);
        if($item->status == 'available') {
            $item->status = 'borrowed';
            $item->save();
            return "Item borrowed successfully.";
        }
        return "Item is not available.";
    }

    public function return($id) {
        $item = Inventory::findOrFail($id);
        $item->status = 'available';
        $item->save();
        return "Item returned successfully.";
    }
}

<?php
// database/migrations/2025_11_20_000000_create_inventories_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration {
    public function up() {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('quantity')->default(0);
            $table->string('status')->default('available');
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('inventories'); }
}

<?php
// app/Models/Inventory.php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model {
    use HasFactory;
    protected $fillable = ['name', 'quantity', 'status'];
}
