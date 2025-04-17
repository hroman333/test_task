@extends('adminlte::page')

@section('title', 'Редагувати працівника')

@section('content_header')
    <h1>Редагувати працівника: {{ $employee->name }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')


                <div class="row">

                    <div class="col-12 text-center">
                        <div class="form-group">
                            <img id="photoPreview"
                                 src="{{ $employee->photo_path ? asset('storage/' . $employee->photo_path) : asset('images/default-avatar.png') }}"
                                 class="img-circle elevation-2" width="100" alt="Поточне фото">
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for="photo">Фото</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('photo') is-invalid @enderror"
                                       id="photo" name="photo" accept="image/*">
                                <label class="custom-file-label" for="photo">
                                    {{ $employee->photo_path ? 'Змінити фото' : 'Оберіть файл' }}
                                </label>
                                @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">Залиште порожнім, щоб залишити поточне фото</small>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for="name">ПІБ*</label>
                            <input type="text" name="name" id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $employee->name) }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for="position_id">Посада*</label>
                            <select name="position_id" id="position_id" class="form-control @error('position_id') is-invalid @enderror" required>
                                <option value="">-- Оберіть посаду --</option>
                                @foreach($positions as $position)
                                    <option value="{{ $position->id }}" {{ (old('position_id', $employee->position_id) == $position->id) ? 'selected' : '' }}>
                                        {{ $position->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('position_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for="date_of_employment">Дата прийому*</label>
                            <input type="date" name="date_of_employment" id="date_of_employment"
                                   class="form-control @error('date_of_employment') is-invalid @enderror"
                                   value="{{ old('date_of_employment', $employee->date_of_employment) }}" required>
                            @error('date_of_employment')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for="phone">Телефон*</label>
                            <input type="tel" name="phone" id="phone"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $employee->phone) }}" required>
                            @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for="email">Email*</label>
                            <input type="email" name="email" id="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $employee->email) }}" required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for="salary">Зарплата ($)*</label>
                            <input type="number" name="salary" id="salary"
                                   class="form-control @error('salary') is-invalid @enderror"
                                   value="{{ old('salary', $employee->salary) }}" min="0" step="0.01" required>
                            @error('salary')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for="manager_name">Ім'я керівника</label>
                            <input type="text" name="manager_name" id="manager_name"
                                   class="form-control @error('manager_name') is-invalid @enderror"
                                   value="{{ old('manager_name', optional($employee->manager)->name) }}"
                                   placeholder="Введіть ім'я керівника">
                            <ul id="managerSuggestions" class="list-group position-absolute d-none"></ul>
                            @error('manager_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for="created_at">Created At</label>
                            <input type="text" id="created_at" class="form-control"
                                   value="{{ $employee->created_at->format('Y-m-d H:i:s') }}" readonly>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for="updated_at">Last Updated At</label>
                            <input type="text" id="updated_at" class="form-control"
                                   value="{{ $employee->updated_at->format('Y-m-d H:i:s') }}" readonly>
                        </div>
                    </div>

                    <div class="col-12 text-right">
                        <a href="{{ route('employees.index') }}" class="btn btn-default mr-2">
                            <i class="fas fa-arrow-left"></i> Скасувати
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Оновити дані
                        </button>
                    </div>
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
                            input.dataset.managerId = employee.id;
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

