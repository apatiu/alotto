.<template>
    <label>
        <slot></slot>
    </label>
</template>

<script>
    export default {
        name: 'ALabel',
        props: ['value']
    }
</script>
