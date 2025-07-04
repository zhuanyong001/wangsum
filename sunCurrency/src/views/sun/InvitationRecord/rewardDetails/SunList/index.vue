<template>
  <div class="SunList">
    <div class="table">
      <table>
        <thead>
          <tr>
            <th v-for="column in columns" :key="column.key" :style="{ textAlign: column.align }">
              {{ column.title }}
            </th>
          </tr>
        </thead>
        <tbody v-if="list.length > 0">
          <tr v-for="(item, index) in list" :key="index">
            <td v-for="column in columns" :key="column.key">
              <div class="td-div" >{{ getNestedProperty(item, column.dataIndex)}}</div>
            </td>
          </tr>
        </tbody>
      </table>
      <!-- <a-empty v-if="list.length <= 0 && !loading" :description="null" />
      <div class="example" v-if="loading">
        <a-spin />
      </div> -->
    </div>
  </div>
</template>
  
<script setup lang="ts">
  import { TableColumn } from '#/config'
  defineProps({
    list: {
      type: Array,
      default: () => [],
    },
    columns: {
      type: Array,
    },
    loading:{
      type:Boolean,
      required: true
    }
  })
  function getNestedProperty(obj:object, path:string) {
    const keys = path.split('.');

    return keys.reduce((acc, key) => (acc && acc[key] !== undefined) ? acc[key] : undefined, obj);
  }
</script>
  
<style lang="scss" scoped>
.SunList {
  padding: 1.25rem;
  border: 1px solid var(--borderColor);
  border-radius: 0.83rem;
  background: var(--bgColor3);
  box-sizing: border-box;
  width: 100%;;
  .title {
    font-size: 1.42rem;
    color: var(--textColor);
    margin-bottom: 1.25rem;
  }

  .table {
    overflow-x: auto;
    width: 100%;
    table {
      width: 100%;
      border-collapse: collapse;

      thead {
        color: var(--textColor2);

        tr {
          border-bottom: 1px solid var(--borderColor);
        }

        th {
          padding-bottom: 0.625rem;
          color: var(--textColor2);
          border: none;
        }
      }

      tbody {
        border-bottom: 1px solid var(--borderColor);
        tr {
          td {
            padding: 0.625rem;
            border: none;
            color: var(--textColor);

            &.td-1 .name {
              margin-left: 0.58rem;
            }
            .td-div{
              // white-space: nowrap;
              // max-width: 5.5rem;
              word-wrap: break-word;
              text-align: left;
            }
          }

          &:last-child {
            border-bottom: none;
          }

          &:hover {
            background: rgba(0, 0, 0, 0.05);
          }
        }
      }
    }

    .allAmount {
      color: var(--textColor2);
      font-size: 1rem;
    }
    .more-text {
      color: var(--textColor2);
    }
    .empty {
      width: 100%;
      margin: 4rem auto;
      display: flex;
      justify-content: center;
    }
  }
}
</style>
  