@extends('admin.master')
@section('head_styles')
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div>
                    <a class="btn btn-primary" href="{{ route('admin.product.index') }}">Danh sách sản phẩm</a>
                </div>
                <br/>
                <div class="panel panel-default">
                    <div class="panel-heading text-center">Cập nhật sản phẩm</div>
                    <div class="panel-body">
                        <form enctype="multipart/form-data" action="{{ route('admin.product.update', ['id' => $product->id]) }}" method="post">
                            {{ csrf_field() }}
                            {{ method_field('put') }}
                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                <label for="name">Tên sản phẩm</label>
                                <input type="text" class="form-control" id="name" name="name"
                                       placeholder="ghi tên sản phẩm vào đây" value="{{ $product->name }}">
                                <span class="help-block">{{ $errors->first('name') }}</span>
                            </div>

                            <div class="form-group {{ $errors->has('code') ? 'has-error' : '' }}">
                                <label for="code">Mã sản phẩm</label>
                                <input type="text" class="form-control" id="code" name="code"
                                       placeholder="ghi mã sản phẩm vào đây" value="{{ $product->code }}">
                                <span class=" help-block">{{ $errors->first('code') }}</span>
                            </div>

                            <div class="form-group {{ $errors->has('content') ? 'has-error' : '' }}">
                                <label for="content">Nội dung</label>
                                <textarea class="form-control" rows="5" id="content" name="content"
                                          placeholder="ghi nội dung vào đây">{{ $product->content }}</textarea>
                                <span class=" help-block">{{ $errors->first('content') }}</span>
                            </div>

                            <div class="form-group {{ $errors->has('regular_price') ? 'has-error' : '' }}">
                                <label for="regular_price">Giá thị trường</label>
                                <input type="number" class="form-control" id="regular_price" name="regular_price"
                                       placeholder="ghi giá thị trường vào đây" value="{{ $product->regular_price }}">
                                <span class=" help-block">{{ $errors->first('regular_price') }}</span>
                            </div>

                            <div class="form-group {{ $errors->has('sale_price') ? 'has-error' : '' }}">
                                <label for="sale_price">Giá bán</label>
                                <input type="number" class="form-control" id="sale_price" name="sale_price"
                                       placeholder="ghi giá bán vào đây" value="{{ $product->sale_price }}">
                                <span class=" help-block">{{ $errors->first('sale_price') }}</span>
                            </div>

                            <div class="form-group {{ $errors->has('original_price') ? 'has-error' : '' }}">
                                <label for="original_price">Giá gốc</label>
                                <input type="number" class="form-control" id="original_price" name="original_price"
                                       placeholder="ghi giá gốc vào đây" value="{{ $product->original_price }}">
                                <span class=" help-block">{{ $errors->first('original_price') }}</span>
                            </div>

                            <div class="form-group {{ $errors->has('quantity') ? 'has-error' : '' }}">
                                <label for="quantity">Số lượng</label>
                                <input type="number" class="form-control" id="quantity" name="quantity"
                                       placeholder="ghi số lượng vào đây" value="{{ $product->quantity }}">
                                <span class=" help-block">{{ $errors->first('quantity') }}</span>
                            </div>

                            {{--Hình sản phẩm--}}
                            <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
                                <label for="image">Hình ảnh</label>
                                <div>
                                    @if( ! empty($product->image) && file_exists(public_path("uploaded/$product->image")))
                                        <img src="{{ asset(get_thumbnail('uploaded/' . $product->image)) }}" alt="" class="img-responsive">
                                    @else
                                        <span style="color: red">Chưa có hình ảnh</span>
                                    @endif
                                </div>
                                <input type="file" class="form-control" id="image" name="image"
                                       value="{{ $product->image }}">
                                <span class=" help-block">{{ $errors->first('image') }}</span>
                            </div>

                            {{--Upload thư viện hình ảnh--}}
                            {{--{{ dd($errors->all()) }}--}}
                            <div class="form-group {{ $errors->has('images.*') ? 'has-error' : '' }}">
                                <label for="images">Thư viện hình của sản phẩm</label>
                                <div>
                                    @forelse($product->attachments as $imageFile)
                                        @if( ! empty($imageFile->path) && file_exists(public_path("uploaded/$imageFile->path")))
                                            <img src="{{ asset(get_thumbnail('uploaded/' . $imageFile->path)) }}" alt="">
                                        @endif
                                    @empty
                                        <span style="color: red">Chưa có hình ảnh</span>
                                    @endforelse
                                </div>
                                <input type="file" class="form-control" id="images" name="images[]"
                                       value="{{ old('images') }}" multiple>
                                <span class=" help-block">{{ $errors->first('images.*') }}</span>
                            </div>

                            {{--Chuyên mục--}}
                            <div class="form-group {{ $errors->has('category_id') ? 'has-error' : '' }}">
                                <label for="category_id">Chuyên mục</label>
                                <select name="category_id" id="category_id" class="form-control">
                                    {{-- $categories[0] là 1 mảng các category chung chung (có parent = 0) --}}
                                    @if(count($categories[0]) > 0)
                                        @foreach($categories[0] as $cate_dad)
                                            @if($cate_dad->id != 1) {{-- category Uncategorized thì k có cate con --}}
                                            <optgroup label="{{ $cate_dad->name }}">
                                                @if(isset($categories[$cate_dad->id]))
                                                    @foreach($categories[$cate_dad->id] as $cate)
                                                        <option value="{{ $cate->id }}" {{ $product->category_id == $cate->id ? 'selected' : '' }}>{{ $cate->name }}</option>
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                            @else
                                                <option value="1" {{ old('category_id') == 1 ? 'selected' : '' }}>Uncategorized</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                <span class="help-block">{{ $errors->first('category_id') }}</span>
                            </div>

                            {{--Tags--}}
                            <div class="form-group">
                                <label for="tags" style="display: block">Các tags</label>
                                <select name="tags[]" id="tags" class="form-control" multiple="multiple" style="width: 80%">
                                    {{--ng dùng điền các tags vô đây--}}
                                    {{--Giữ lại giá trị cũ cho ngta--}}
                                    @if($product->tags)
                                        @foreach($product->tags as $tag)
                                            <option value="{{ $tag->name }}" selected="selected">{{ $tag->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            {{--Thuộc tính--}}
                            <div class="form-group" id="henry-app">
                                <henry-attributes></henry-attributes>
                            </div>

                            <button type="submit" class="btn btn-success">Lưu</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('body_scripts_bottom')
    <script src="{{ asset('js/vue.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script>
        $("#tags").select2({
            tags: true,
            tokenSeparators: [',']
        })
    </script>
    <script type="text/x-template" id="henry-attributes-template">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Thuộc tính</th>
                <th>Giá trị</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(item, key) in attributes">
                <td><input type="text" v-bind:name="'attributes['+ key +'][name]'" v-model="item.name" class="form-control" placeholder="Thuộc tính"></td>
                <td><input type="text" v-bind:name="'attributes['+ key +'][value]'" v-model="item.value" class="form-control" placeholder="Giá trị"></td>
                <td>
                    <button type="button" v-if="key != 0" v-on:click="deleteAttribute(item)" class="btn btn-danger"><i class="glyphicon glyphicon-trash"></i></button>
                    <button type="button" v-if="key == (attributes.length - 1)" v-on:click="addAttribute" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i></button>
                </td>
            </tr>
            </tbody>
        </table>
    </script>
    <script>
        Vue.component('henry-attributes', {
            template: '#henry-attributes-template',
            data: function(){
                var attributes = null;
                @if($product->attributes)
                    attributes = {!! $product->attributes !!};
                @endif
                if(attributes.length == 0 || attributes == null){
                    attributes = [
                        { name: '', value: ''}
                    ];
                }
                return {
                    attributes: attributes
                };
            },
            methods: {
                addAttribute: function () {
                    this.attributes.push({ name: '', value: ''});
                },
                deleteAttribute: function (item) {
                    this.attributes.splice(this.attributes.indexOf(item), 1);
                }
            }
        });
        new Vue({
            el: '#henry-app'
        });
    </script>
@endsection