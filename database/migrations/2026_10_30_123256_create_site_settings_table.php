<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations. One row only: values shared by the header,
     * footer and WhatsApp button. See SiteSetting::current().
     */
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable();
            $table->string('footer_logo')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('whatsapp_number')->nullable(); // digits only, for https://wa.me/{number}
            $table->string('footer_tagline')->nullable();
            $table->string('footer_tagline_ar')->nullable();
            $table->string('cr_number')->nullable();  // Saudi commercial registration
            $table->string('vat_number')->nullable();
            $table->json('footer_links')->nullable(); // [{heading, links: [{label, url}]}]
            $table->json('cities')->nullable();       // ["Riyadh", "Jeddah", ...]
            $table->json('social_links')->nullable(); // [{platform, url}]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
