@extends('layouts.app')

@section('content')
<div id="app" class="min-h-screen bg-gradient-to-b from-blue-50 to-white">
    @if($activeBatch && $activeBatch->status == 'open')
        <!-- Landing Page -->
        <div v-if="!showForm && !showConfirmation">
            <!-- Hero Section -->
            <div class="relative overflow-hidden bg-gradient-to-r from-red-600 to-red-700 text-white">
                <div class="absolute inset-0 bg-black opacity-20"></div>
                <div class="relative container mx-auto px-4 py-20">
                    <div class="text-center">
                        <h1 class="text-5xl font-bold mb-4 animate-fade-in"> বাংলায় IELTS প্রাইভেট ব্যাচে ভর্তি</h1>
                        
                        <div class="flex justify-center items-center gap-4 mb-8">
                            <span class="bg-white bg-opacity-20 px-4 py-2 rounded-full text-sm">
                                <svg class="w-5 h-5 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                                </svg>
                                এই কোর্সের কোন রিফান্ড নীতি নেই 
                            </span>
                            <span class="bg-white bg-opacity-20 px-4 py-2 rounded-full text-sm">
                                <svg class="w-5 h-5 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 7a1 1 0 00-1 1v4a1 1 0 001 1h4a1 1 0 001-1V8a1 1 0 00-1-1H8z" clip-rule="evenodd"/>
                                </svg>
                                {{ $activeBatch->name }} Batch
                            </span>
                        </div>
                        <button @click="scrollToCourse" class="bg-white text-red-600 px-8 py-4 rounded-full font-semibold text-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                            কোর্স বিস্তারিত দেখুন
                            <svg class="w-5 h-5 inline ml-2 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0">
                    
                        <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="white"/>
                    </svg>
                </div>
            </div>

            <!-- Features Section -->
            <div class="container mx-auto px-4 py-16">
                <div class="grid md:grid-cols-3 gap-8 mb-16">
                    <div class="text-center transform hover:scale-105 transition-all duration-300">
                        
                       
                    </div>

                    <div class="text-center transform hover:scale-105 transition-all duration-300">
                        
                      
                    </div>

                    <div class="text-center transform hover:scale-105 transition-all duration-300">
                        
                        
                    </div>
                </div>

             <!-- Course Details Card -->
<div id="course-details" class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg border border-red-100 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-rose-500 to-pink-500 p-6 text-black text-center">
            <h2 class="text-2xl font-bold">কোর্স বিস্তারিত</h2>
        </div>
        
        <div class="p-8">
            <!-- Course Info List -->
            <div class="space-y-5 mb-8">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-rose-500 rounded-full mr-4 flex-shrink-0"></div>
                    <span class="text-gray-800 text-lg">
                        <span class="font-semibold">মেয়াদ:</span>
                        <span class="ml-2 font-medium">{{ $courseSetting->duration ?? '2 মাস' }}</span>
                    </span>
                </div>
                
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-rose-500 rounded-full mr-4 flex-shrink-0"></div>
                    <span class="text-gray-800 text-lg">
                        <span class="font-semibold">ক্লাস সময়সূচী:</span>
                        <span class="ml-2 font-medium">{{ $courseSetting->classes ?? 'সপ্তাহে 3 দিন' }}</span>
                    </span>
                </div>
                
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-rose-500 rounded-full mr-4 flex-shrink-0"></div>
                    <span class="text-gray-800 text-lg">
                        <span class="font-semibold">স্টাডি ম্যাটেরিয়াল:</span>
                        <span class="ml-2 font-medium">{{ $courseSetting->materials ?? 'Free PDF + Videos' }}</span>
                    </span>
                </div>
                
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-rose-500 rounded-full mr-4 flex-shrink-0"></div>
                    <span class="text-gray-800 text-lg">
                        <span class="font-semibold">প্ল্যাটফর্ম:</span>
                        <span class="ml-2 font-medium">{{ $courseSetting->mock_tests ?? '5টি' }}</span>
                    </span>
                </div>
            </div>
            
            <!-- Price and CTA -->
            <div class="bg-red-50 rounded-xl p-8 text-center">
                <div class="mb-6">
                    <span class="text-gray-700 text-lg">কোর্স ফি</span>
                    <div class="text-4xl font-bold text-rose-600 mt-2">৳{{ number_format($courseSetting->fee ?? 0) }}</div>
                </div>
                
                <button @click="startAdmission" class="bg-gradient-to-r from-rose-500 to-pink-500 text-red px-10 py-4 rounded-full font-bold text-lg hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                    এখনই ভর্তি হন
                </button>
                
                <p class="text-base text-red-600 mt-4 font-semibold flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    কোন রিফান্ড নীতি নেই
                </p>
            </div>
        </div>
    </div>
</div>

                <!-- Contact Section -->
                
            </div>
        </div>

        <!-- YouTube Check Modal -->
        <div v-if="showYoutubeCheck" class="fixed inset-0 bg-gray-900 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
            <div class="relative mx-auto p-8 w-full max-w-md bg-white rounded-2xl shadow-2xl transform transition-all">
                <div class="text-center mb-6">
                    <div class="bg-red-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                        </svg>
                    </div>
                    
                    <p class="text-gray-600">আমাদের YouTube ক্লাস বুঝতে পারছেন?</p>
                </div>
                
                <div class="space-y-4">
                    <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors" :class="{'border-blue-500 bg-blue-50': understandsYoutube === 'yes'}">
                        <input type="radio" v-model="understandsYoutube" value="yes" class="mr-3 text-blue-600">
                        <span class="text-lg">হ্যাঁ, আমি বুঝতে পারছি</span>
                    </label>
                    <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors" :class="{'border-blue-500 bg-blue-50': understandsYoutube === 'no'}">
                        <input type="radio" v-model="understandsYoutube" value="no" class="mr-3 text-blue-600">
                        <span class="text-lg">না, আমার সাহায্য প্রয়োজন</span>
                    </label>
                </div>
                
                <button @click="checkYoutube" :disabled="!understandsYoutube" 
                    class="mt-6 w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors">
                    Continue
                </button>
            </div>
        </div>

        <!-- Main Form -->
        <div v-if="showForm && !showConfirmation" class="container mx-auto px-4 py-12">
            <div class="max-w-3xl mx-auto">
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-red-500 to-red-600 p-8 text-white text-center">
                        <h2 class="text-3xl font-bold mb-2">বাংলায় IELTS প্রাইভেট ব্যাচে ভর্তি</h2>
                        <p class="opacity-90">Fill in your details to secure your seat</p>
                    </div>
                    
                    <form @submit.prevent="submitForm" class="p-8 space-y-6">
                        <!-- Progress Bar -->
                        <div class="mb-8">
                            <div class="flex justify-between mb-2">
                                <span class="text-sm text-gray-600">Progress</span>
                                <span class="text-sm text-gray-600">@{{ formProgress }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-red-600 h-2 rounded-full transition-all duration-300" :style="{width: formProgress + '%'}"></div>
                            </div>
                        </div>

                        <!-- Personal Information Section -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="font-semibold text-lg mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                                Personal Information
                            </h3>
                            
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                                    <input type="text" v-model="form.name" required 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="আপনার সম্পূর্ণ নাম">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                                    <input type="email" v-model="form.email" required 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="আপনার ইমেইল দিন">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp Number *</label>
                                    <input type="tel" v-model="form.phone" required 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="আপনার WhatsApp নাম্বার">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Profession *</label>
                                    <select v-model="form.profession" @change="updateClassTime" required 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                        <option value="">আপনার পেশা</option>
                                        <option value="student">Student</option>
                                        <option value="job_holder">Job Holder</option>
                                        <option value="housewife">Housewife</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Course Information Section -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="font-semibold text-lg mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                </svg>
                                Course Selection
                            </h3>
                            
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Course Type *</label>
                                    <div class="flex gap-4">
                                        <label class="flex items-center px-4 py-2 border-2 rounded-lg cursor-pointer hover:bg-gray-100" 
                                            :class="{'border-blue-500 bg-blue-50': form.course_type === 'academic'}">
                                            <input type="radio" v-model="form.course_type" value="academic" required class="mr-2">
                                            <span>Academic</span>
                                        </label>
                                        <label class="flex items-center px-4 py-2 border-2 rounded-lg cursor-pointer hover:bg-gray-100"
                                            :class="{'border-blue-500 bg-blue-50': form.course_type === 'gt'}">
                                            <input type="radio" v-model="form.course_type" value="gt" required class="mr-2">
                                            <span>General Training</span>
                                        </label>
                                    </div>
                                </div>

                               <div>
    <label class="block text-sm font-medium text-gray-700 mb-1">লিসেনিং এবং রিডিং এ ৪০ টায় কয়টা কারেক্ট হয়?</label>
    <select v-model="form.score" @change="updateClassTime" required 
        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
        <option value="">Select Score</option>
        @for($i = 0; $i <= 40; $i += 5)
            <option value="{{ $i }}">{{ $i }} out of 40</option>
        @endfor
    </select>
</div>
                            </div>
                        </div>

                        <!-- Class Time Display -->
                        <div v-if="classTime" class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-lg">
                            <h3 class="font-semibold text-lg mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                Your Class Schedule
                            </h3>
                            <div class="grid md:grid-cols-3 gap-4 text-center">
                                <div class="bg-white p-4 rounded-lg">
                                    <p class="text-sm text-gray-600">Batch</p>
                                    <p class="font-semibold text-lg">{{ $activeBatch->name }}</p>
                                </div>
                                <div class="bg-white p-4 rounded-lg">
                                    <p class="text-sm text-gray-600">Time</p>
                                    <p class="font-semibold text-lg">@{{ classTime }}</p>
                                </div>
                                <div class="bg-white p-4 rounded-lg">
                                    <p class="text-sm text-gray-600">Days</p>
                                    <p class="font-semibold text-lg">Sun, Tue, Thu</p>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Section -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="font-semibold text-lg mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                </svg>
                                Payment Information
                            </h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method *</label>
                                    <select v-model="form.payment_method" @change="updatePaymentDetails" required 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                        <option value="">Select Payment Method</option>
                                        @foreach($paymentMethods as $method)
                                            <option value="{{ $method->name }}">{{ $method->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Payment Details -->
                                <div v-if="selectedPaymentMethod" class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-yellow-600 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                        <div class="flex-1">
                                            <p class="font-semibold text-yellow-800">@{{ selectedPaymentMethod.name }} Payment Details:</p>
                                            <p class="text-yellow-700 mt-1">Account: @{{ selectedPaymentMethod.account_number }}</p>
                                            <p class="text-yellow-700">Amount: ৳{{ number_format($courseSetting->fee ?? 0) }}</p>
                                            <p class="text-sm text-yellow-600 mt-2">@{{ selectedPaymentMethod.instructions }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Transaction ID *</label>
                                    <input type="text" v-model="form.payment_id" required 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="Enter your transaction ID">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Screenshot (Optional)</label>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label class="relative cursor-pointer bg-white rounded-md font-medium text-red-600 hover:text-blue-500">
                                                    <span>Upload a file</span>
                                                    <input type="file" @change="handleFileUpload" accept="image/*" class="sr-only">
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                            <p v-if="form.screenshot" class="text-sm text-green-600 mt-2">
                                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                @{{ form.screenshot.name }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" :disabled="isSubmitting" 
                            class="w-full bg-gradient-to-r from-red-600 to-red-600 text-white py-4 px-6 rounded-lg font-semibold text-lg hover:shadow-xl transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span v-if="!isSubmitting" class="flex items-center justify-center">
                                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000 2H6a2 2 0 00-2 2v6a2 2 0 002 2h2a1 1 0 100 2H6a4 4 0 01-4-4V7a4 4 0 014-4h5a1 1 0 011 1v1h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.586l-.707.707A1 1 0 019 16v-2a1 1 0 100 2h.586l.707-.707A1 1 0 0111 15h3a4 4 0 004-4V7a4 4 0 00-4-4H9z" clip-rule="evenodd"/>
                                </svg>
                                Submit Application
                            </span>
                            <span v-else class="flex items-center justify-center">
                                <svg class="animate-spin h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Confirmation Page -->
        <div v-if="showConfirmation" class="container mx-auto px-4 py-12">
            <div class="max-w-3xl mx-auto">
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-red-500 to-red-600 p-12 text-white text-center">
                        <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h2 class="text-4xl font-bold mb-4">Application Submitted Successfully!</h2>
                        <p class="text-xl opacity-90">আপনার আবেদন সফলভাবে জমা হয়েছে</p>
                    </div>
                    
                    <div class="p-8">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 mb-6">
                            <h3 class="font-semibold text-lg mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                Application Summary
                            </h3>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Student Name</p>
                                    <p class="font-medium">@{{ confirmationData.name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Batch</p>
                                    <p class="font-medium">@{{ confirmationData.batch }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Class Time</p>
                                    <p class="font-medium">@{{ confirmationData.class_time }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Course Fee</p>
                                    <p class="font-medium">৳{{ number_format($courseSetting->fee ?? 0) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Payment Method</p>
                                    <p class="font-medium">@{{ form.payment_method }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Transaction ID</p>
                                    <p class="font-medium">@{{ confirmationData.payment_id }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 mb-6">
                            <h3 class="font-semibold text-lg mb-3 flex items-center text-yellow-800">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                What Happens Next?
                            </h3>
                            <ul class="space-y-2 text-yellow-700">
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Payment verification চলছে (24-48 hours)
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Confirmation SMS/Email পাবেন
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Class শুরুর আগে reminder পাবেন
                                </li>
                            </ul>
                        </div>

                        <div class="text-center">
                            <p class="text-gray-600 mb-4">Need assistance?</p>
                            <a href="tel:{{ $courseSetting->contact_number }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                </svg>
                                {{ $courseSetting->contact_number ?? 'Contact Support' }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Batch Closed Message -->
        <div class="min-h-screen flex items-center justify-center">
            <div class="text-center">
                <div class="bg-red-100 w-32 h-32 rounded-full flex items-center justify-center mx-auto mb-8">
                    <svg class="w-20 h-20 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Admissions Closed</h2>
                <p class="text-xl text-gray-600 mb-8">{{ $activeBatch->name ?? 'Current' }} Batch এর এডমিশন শেষ হয়ে গেছে</p>
                <div class="bg-blue-50 rounded-xl p-8 max-w-md mx-auto">
                    <svg class="w-12 h-12 text-blue-600 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <h3 class="text-2xl font-semibold mb-2">Next Batch Coming Soon!</h3>
                    <p class="text-gray-600">পরবর্তী ব্যাচের জন্য অপেক্ষা করুন</p>
                    @if($courseSetting->contact_number)
                    <a href="tel:{{ $courseSetting->contact_number }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 mt-4">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                        </svg>
                        Contact for Updates
                    </a>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.8s ease-out;
}
</style>

<script>
const { createApp } = Vue;

createApp({
    data() {
        return {
            showForm: false,
            showYoutubeCheck: false,
            showConfirmation: false,
            understandsYoutube: '',
            classTime: '',
            selectedPaymentMethod: null,
            isSubmitting: false,
            confirmationData: {},
            form: {
                name: '',
                email: '',
                phone: '',
                course_type: '',
                profession: '',
                score: '',
                payment_method: '',
                payment_id: '',
                screenshot: null
            },
            paymentMethods: @json($paymentMethods)
        }
    },
    computed: {
        formProgress() {
            let filled = 0;
            const fields = ['name', 'email', 'phone', 'course_type', 'profession', 'score', 'payment_method', 'payment_id'];
            fields.forEach(field => {
                if (this.form[field]) filled++;
            });
            return Math.round((filled / fields.length) * 100);
        }
    },
    methods: {
        scrollToCourse() {
            document.getElementById('course-details').scrollIntoView({ behavior: 'smooth' });
        },
        startAdmission() {
            this.showYoutubeCheck = true;
        },
        async checkYoutube() {
            try {
                const response = await axios.post('/check-youtube', {
                    understands: this.understandsYoutube
                });

                if (this.understandsYoutube === 'no' && response.data.youtube_link) {
                    window.open(response.data.youtube_link, '_blank');
                    this.showYoutubeCheck = false;
                } else {
                    this.showYoutubeCheck = false;
                    this.showForm = true;
                }
            } catch (error) {
                console.error(error);
                this.showYoutubeCheck = false;
                this.showForm = true;
            }
        },
        async updateClassTime() {
            if (this.form.profession && this.form.score) {
                try {
                    const response = await axios.post('/get-class-time', {
                        profession: this.form.profession,
                        score: this.form.score
                    });
                    this.classTime = response.data.time;
                } catch (error) {
                    console.error(error);
                }
            }
        },
        updatePaymentDetails() {
            this.selectedPaymentMethod = this.paymentMethods.find(
                method => method.name === this.form.payment_method
            );
        },
        handleFileUpload(event) {
            this.form.screenshot = event.target.files[0];
        },
        async submitForm() {
            this.isSubmitting = true;
            
            const formData = new FormData();
            Object.keys(this.form).forEach(key => {
                if (this.form[key] !== null) {
                    formData.append(key, this.form[key]);
                }
            });

            try {
                const response = await axios.post('/submit-application', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                if (response.data.success) {
                    this.confirmationData = response.data.data;
                    this.showForm = false;
                    this.showConfirmation = true;
                    
                    // Scroll to top
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.data.error || 'Something went wrong',
                        confirmButtonColor: '#3B82F6'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: error.response?.data?.error || 'Something went wrong. Please try again.',
                    confirmButtonColor: '#3B82F6'
                });
            } finally {
                this.isSubmitting = false;
            }
        }
    }
}).mount('#app');

// Set CSRF token for axios
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
</script>
@endsection