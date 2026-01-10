@props(['name', 'length' => 6])

<div x-data="{
        length: {{ $length }},
        value: Array({{ $length }}).fill(''),
        handleInput(index, event) {
            const val = event.target.value;
            
            // Handle number only
            if (val && !/^\d+$/.test(val)) {
                event.target.value = '';
                return;
            }

            this.value[index] = val.slice(-1); // Only take last char if multiple
            event.target.value = this.value[index];

            if (val && index < this.length - 1) {
                this.$refs[`input_${index + 1}`].focus();
            }
            
            this.updateHiddenInput();
        },
        handlePaste(event) {
            event.preventDefault();
            const pastedData = event.clipboardData.getData('text').slice(0, this.length);
            if (!/^\d+$/.test(pastedData)) return;

            pastedData.split('').forEach((char, i) => {
                this.value[i] = char;
                if (this.$refs[`input_${i}`]) {
                    this.$refs[`input_${i}`].value = char;
                }
            });
            
            this.updateHiddenInput();
            const nextIndex = Math.min(pastedData.length, this.length - 1);
            this.$refs[`input_${nextIndex}`].focus();
        },
        handleKeyDown(index, event) {
            if (event.key === 'Backspace') {
                if (!this.value[index] && index > 0) {
                    this.$refs[`input_${index - 1}`].focus();
                }
                this.value[index] = '';
                this.updateHiddenInput();
            }
        },
        updateHiddenInput() {
            this.$refs.hiddenInput.value = this.value.join('');
        }
    }" class="flex items-center gap-2">
    <!-- Hidden input for form submission -->
    <input type="hidden" name="{{ $name }}" x-ref="hiddenInput">

    <div class="flex items-center gap-2">
        @for ($i = 0; $i < $length; $i++)
            <input x-ref="input_{{ $i }}" type="text" inputmode="numeric" maxlength="1"
                class="flex h-10 w-10 items-center justify-center rounded-md border border-input bg-background text-sm text-center ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                @input="handleInput({{ $i }}, $event)" @keydown="handleKeyDown({{ $i }}, $event)"
                @paste="handlePaste($event)" />

            <!-- Separator every 3 inputs (optional, imitating the dash) -->
            @if (($i + 1) % 3 === 0 && $i !== $length - 1)
                <div class="text-muted-foreground">-</div>
            @endif
        @endfor
    </div>
</div>