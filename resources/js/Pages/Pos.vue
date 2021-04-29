<template>
    <div class="p-flex border-b">
        <div class="cell-gold-price">gold price</div>
    </div>
    <div class="p-grid p-mt-2">
        <div class="p-col-8">
            <div class="p-flex-column">
                <div class="cell-sell">
                    <Fieldset legend="ขาย">
                        <div class="p-flex-column">
                            <div>
                                <InputText></InputText>
                                <Button label="S"></Button>
                            </div>
                          <table class="table">
                              <thead>
                                  <tr>
                                      <th>รหัส</th>
                                      <th>ประเภทสินค้า</th>
                                      <th>จำนวน</th>
                                      <th>นน.รวม</th>
                                      <th>ราคารวม</th>
                                      <th>ส่วนลด</th>
                                      <th>มัดจำ</th>
                                      <th>ราคาทองขาย</th>
                                      <th>ค่าแรงขาย</th>
                                      <th>ราคาเปลี่ยน</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <tr></tr>
                              </tbody>
                          </table>
                        </div>
                    </Fieldset>
                </div>
                <div class="cell-buy mt-2">
                    <Fieldset legend="ซื้อ">
                        The story begins as Don Vito Corleone, the head of a New York Mafia family, oversees his
                        daughter's wedding.
                        His beloved son Michael has just come home from the war, but does not intend to become part of
                        his father's business.
                        Through Michael's life the nature of the family business becomes clear. The business of the
                        family is just like the head of the family,
                        kind and benevolent to those who give respect, but given to ruthless violence whenever anything
                        stands against the good of the family.
                    </Fieldset>
                </div>
            </div>
        </div>
        <div class="p-col-4">
            <div class="p-flex-column">
                <div class="cell-cust">cust</div>
                <div class="cell-bill bg-yellow-50 shadow p-4">
                    <p class="text-center font-bold text-sm">สรุปรายการ</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import SelectGoldPercent from "@/A/SelectGoldPercent";
import InputWeight from "@/A/InputWeight";

export default {
    name: "Pos",
    components: {InputWeight, SelectGoldPercent},
    props:{
        bill: Object
    },
    data() {
        return {
            form: this.$inertia.form()
        }
    },
}
</script>

<style scoped>

</style>
