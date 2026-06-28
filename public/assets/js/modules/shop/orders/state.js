export const OrderState = {

    products:{},


    add(product){

        if(this.products[product.id]) return;


        this.products[product.id]={

            id:product.id,
            name:product.name,
            price:Number(product.sale_price || 0),
            quantity:1,
            discount:0,
            gift:false

        };

    },


    get(){

        return Object.values(this.products);

    }

};