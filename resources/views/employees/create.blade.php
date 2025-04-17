@extends('adminlte::page')

@section('title', 'Додати працівника')

@section('content_header')
    <h1>Додати працівника</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data" class="d-flex flex-column">
                @csrf

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="form-group text-center">
                    <img id="photoPreview" src="{{ asset('images/default-avatar.png') }}"
                         class="img-circle elevation-2 mb-3" width="100" alt="Попередній перегляд">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input @error('photo') is-invalid @enderror"
                               id="photo" name="photo" accept="image/*">
                        <label class="custom-file-label" for="photo">Оберіть файл</label>
                        @error('photo')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <small class="form-text text-muted">Формат: JPG, PNG (розмір мінімум 300x300px)</small>
                </div>

                <div class="form-group w-100">
                    <label for="name">ПІБ*</label>
                    <input type="text" name="name" id="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group w-100">
                    <label for="phone">Телефон*</label>
                    <input type="tel" name="phone" id="phone"
                           class="form-control @error('phone') is-invalid @enderror"
                           value="{{ old('phone') }}"
                           placeholder="+380 (XX) XXX XX XX" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Формат: +380 (XX) XXX XX XX</small>
                </div>

                <div class="form-group w-100">
                    <label for="email">Email*</label>
                    <input type="email" name="email" id="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" required>
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group w-100">
                    <label for="position_id">Посада*</label>
                    <select name="position_id" id="position_id"
                            class="form-control @error('position_id') is-invalid @enderror" required>
                        <option value="">-- Оберіть посаду --</option>
                        @foreach($positions as $position)
                            <option value="{{ $position->id }}" {{ old('position_id') == $position->id ? 'selected' : '' }}>
                                {{ $position->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('position_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group w-100">
                    <label for="salary">Зарплата ($)*</label>
                    <input type="number" name="salary" id="salary"
                           class="form-control @error('salary') is-invalid @enderror"
                           value="{{ old('salary') }}" min="0" step="0.01" required>
                    @error('salary')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group w-100">
                    <label for="manager_name">Ім'я керівника</label>
                    <input type="text" name="manager_name" id="manager_name"
                           class="form-control @error('manager_name') is-invalid @enderror"
                           placeholder="Введіть ім'я керівника" autocomplete="off">
                    <input type="hidden" name="manager_id" id="manager_id">
                    <ul id="managerSuggestions" class="list-group position-absolute d-none"></ul>
                    @error('manager_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Почніть вводити ім'я, щоб побачити пропозиції.</small>
                </div>

                <div class="form-group w-100">
                    <label for="date_of_employment">Дата прийому*</label>
                    <input type="date" name="date_of_employment" id="date_of_employment"
                           class="form-control @error('date_of_employment') is-invalid @enderror"
                           value="{{ old('date_of_employment', now()->format('Y-m-d')) }}" required>
                    @error('date_of_employment')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group text-right mt-4 w-100">
                    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary mr-2">
                        <i class="fas fa-times"></i> Скасувати
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Додати
                    </button>
                </div>
            </form>
        </div>
    </div>
@stop



@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const input = document.getElementById('manager_name');
            const suggestions = document.getElementById('managerSuggestions');
            const managerIdInput = document.getElementById('manager_id');

            input.addEventListener('input', async () => {
                const query = input.value.trim();

                if (query.length < 2) {
                    suggestions.classList.add('d-none');
                    return;
                }

                try {
                    const response = await fetch(`/employees/search?query=${encodeURIComponent(query)}`);
                    const employees = await response.json();

                    suggestions.innerHTML = '';
                    if (employees.length === 0) {
                        suggestions.classList.add('d-none');
                        return;
                    }

                    employees.forEach(employee => {
                        const li = document.createElement('li');
                        li.textContent = employee.name;
                        li.classList.add('list-group-item', 'list-group-item-action');
                        li.dataset.id = employee.id;
                        suggestions.appendChild(li);

                        li.addEventListener('click', () => {
                            input.value = employee.name;
                            managerIdInput.value = employee.id;
                            suggestions.classList.add('d-none');
                        });
                    });

                    suggestions.classList.remove('d-none');
                } catch (error) {
                    console.error('Error fetching employees:', error);
                }
            });
        });

    </script>
@stop
