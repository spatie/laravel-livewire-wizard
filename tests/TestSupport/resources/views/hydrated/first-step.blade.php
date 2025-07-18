<div>
    <span>first step</span>
    <span>{{ $this->userForm->name }}</span>
    <span>{{ $this->userForm->email }}</span>
    <span>{{ $this->userForm->likes_coffee?->value ?? '' }}</span>
</div>