<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePaymentOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('contract_subcontractor_id');
            $table->bigInteger('subcontractor_id');
            $table->bigInteger('project_id');
            $table->bigInteger('created_by');
            $table->dateTime('payment_date');
            $table->text('content');
            $table->tinyInteger('type_payment')->comment('1: quyết toán, 2: thanh toán, 3: tạm ứng');
            $table->tinyInteger('status')->comment('( chưa duyệt, chưa thanh toán, đã thanh toán, từ chối ) mặc định khi mới tạo xong sẽ để là chưa duyệt, khi gửi lên kế toán thì sẽ do kế toán cập nhập các trạng thái cho phù hợp )');
            $table->double('contract_value')->comment('( Lấy giá trị hợp đồng có VAT, trường hợp không có thì lấy giá trị họp đồng không có VAT)');
            $table->double('settlement_value');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_orders');
    }
}
