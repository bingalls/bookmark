<template>
<div>
    <section class="itemapp">
      <header class="header">
        <h1>Bookmarks</h1>
        <label>
            <input class="new-item"
              autofocus autocomplete="off"
              placeholder="Add a URL to bookmark"
              v-model="newItem"
              @keyup.enter="addItem()">
          </label>
      </header>
      <section class="main" v-show="items.length" v-cloak>
        <input id="toggle-all" class="toggle-all" type="checkbox">
        <label for="toggle-all"></label>
        <ul class="item-list">
          <li v-for="item in items"
            class="item"
            :key="item.id"
            :class="{ completed: item.is_complete, editing: item === editedItem }">
            <div class="view">
              <input class="toggle" type="checkbox" v-model="item.is_complete" v-on:change="activeItemNow(item.id, item.is_complete)" >
              <label @dblclick="editItem(item)">{{ item.name }}</label>
              <button class="destroy" @click="deleteItem(item.id)"></button>
            </div>
            <input class="edit" type="text"
              v-model="name"
              v-item-focus="item === editedItem"
              @blur="doneEdit(item.id)"
              @keyup.enter="doneEdit(item.id)"
              @keyup.esc="cancelEdit()">
          </li>
        </ul>
      </section>
      <footer class="footer" v-if="hasItem">
        <span class="item-count">
          <strong>{{ activeItem }}</strong> inactive items
        </span>
<!--        <ul class="filters">-->
<!--          <li><a href="javascript:void(0)" :class="{ selected: visibility == 'all' }" @click="filterNow('all')">All</a></li>-->
<!--          <li><a href="javascript:void(0)" :class="{ selected: visibility == 'active' }" @click="filterNow('active')">Active</a></li>-->
<!--          <li><a href="javascript:void(0)" :class="{ selected: visibility == 'completed' }" @click="filterNow('completed')">Completed</a></li>-->
<!--        </ul>-->
<!--        <button class="clear-completed" v-if="hasCompleted" @click="deleteItem('')">-->
<!--          Clear completed-->
<!--        </button>-->
      </footer>
    </section>
    <p>Double click to edit item</p>
    <p>Laravel 6.x and Vue 2 <a href="https://github.com/bingalls" target="_blank">github</a> </p>
</div>


</template>

<script>
import axios from 'axios'
let token = document.head.querySelector('meta[name="csrf-token"]');

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}
export default {
    data () {
      return {
        newItem: '',
        selected: false,
        activeItem: '',
        items: [],
        visibility: '',
        editedItem: null,
        beforeEditCache: '',
        hasItem: false,
        hasCompleted: false,
        name: ''
      }
  },
  methods: {
    addItem: function () {
        axios.post('item/add',{
                name: this.newItem
        }).then((response)=>{
            this.newItem = ''
            axios.get('item/get',{
                params: {
                    type: this.visibility
                }
            }).then((response)=>{
                this.items = response.data.item
                if(this.items.length>0){
                    this.hasItem = true
                }
                this.activeItem = response.data.active_now
            })
        })
  },

  deleteItem: function (e) {
      axios.get('item/delete',{
          params: {
              type: e !== '' ? 'single' : '',
              id: e
          }
      }).then((response)=>{
          axios.get('item/get',{
              params: {
                  type: this.visibility
              }
          }).then((response)=>{
              this.items = response.data.item
              this.activeItem = response.data.active_now
              if(response.data.has_completed > 0) {
                  this.hasCompleted = true
              }else{
                  this.hasCompleted = false
              }
              if(response.data.total>0){
                  this.hasItem = true
              }else{
                  this.hasItem = false
              }
          })
      })
  },
  activeItemNow: function(id,e) {

      axios.get('item/action',{
          params:{
              type: e ? 'complete' : ' ',
              id:id
          }
      }).then((response)=>{
          Fire.$emit('get_data')
      })
  },

  filterNow: function(e){
      this.visibility = e;
      axios.get('item/get',{
          params:{
              type: this.visibility
          }
      }).then((response)=>{
          if (response.data.item.length > 0) {
              this.activeItem = response.data.active_now
          }
          this.items = response.data.item
      })
  },
  editItem: function (item) {
      this.name = item.name
      this.editedItem = item
  },
  doneEdit: function(id){
      axios.get('item/update',{
          params:{
              id: id,
              name: this.name
          }
      }).then((response)=>{
          if (!this.editedItem) {
            return
          }
          this.editedItem = null
          axios.get('item/get',{
              params:{
                  type: this.visibility
              }
          }).then((response)=>{
              this.items = response.data.item
              this.activeItem = response.data.active_now
          })
      })
  },
  cancelEdit: function() {
      this.editedItem = null
  }

},
  created: function () {
     this.visibility = 'all'
    axios.get('item/get',{
        params:{
            type: 'all'
        }
    }).then((response)=>{
        this.items = response.data.item
        this.activeItem = response.data.active_now
        if(response.data.has_completed > 0) {
            this.hasCompleted = true
        }
        if(this.items.length>0){
            this.hasItem = true
        }
    })
    Fire.$on('get_data',()=>{
        axios.get('item/get',{
            params:{
                type: this.visibility
            }
        }).then((response)=>{
            this.activeItem = response.data.active_now
            if(response.data.has_completed > 0) {
                this.hasCompleted = true
            }else{
                this.hasCompleted = false
            }
        })
    })


},

directives: {
    'item-focus': function (el, binding) {
      if (binding.value) {
        el.focus()
      }
    }
  }

}
</script>

<!--<style lang="css" scoped >-->
<!--</style>-->
