@extends('layout.layout')
@section('content')
    {{-- Mohammad --}}
    <style>
        input[type="text"],
        input[type="number"] {
            text-indent: 18px;
            outline: none;
            transition: all 700ms;
        }

        input[type="text"]:focus,
        input[type="number"]:focus {
            background-color: rgb(222, 227, 233);
        }

        .franceborder {
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: flex-end;
            align-content: space-between;

        }

        @font-face {
            font-family: "NRT-Reg";
            src: url("NRT-Reg.eot");
            /* IE9 Compat Modes */
            src: url("NRT-Reg.eot?#iefix") format("embedded-opentype");
                /* IE6-IE8 */
            /* Modern Browsers */
            font-weight: normal;
            font-style: normal;
        }


        * {
            font-family: 'NRT-Reg';
            letter-spacing: 0.2rem;
        }

    </style>
    <section class="main">
        <div class="customer-section">
            <div class="heading">
                <h1 dir="rtl"><i class="fas fa-user-friends"><span>زیادکردنی موشتەری</span></i></h1>
            </div>
            <div class="adding">
                {{-- The Form --}}
                <form action="{{ route('customer.store') }}" method="POST" class="franceborder">
                    @csrf
                    <div class="input-form">
                        <label for="cust-name-1"> ناوی موشتەری: </label>
                        <input type="text" id="cust-name-1" name="customerName">
                    </div>
                    @error('customerName')
                        <div id="error">{{ $message }}</div>
                    @enderror
                    <div class="input-form">
                        <label for="phone-num-1"> ژمارەی مۆبایل:</label>
                        <input type="text" id="phone-num-1" name="mobileNumber">
                    </div>
                    @error('mobileNumber')
                        <div id="error">{{ $message }}</div>
                    @enderror
                    <div class="buttons">
                        <button class="form-btn submit-btn"> ناردن</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
