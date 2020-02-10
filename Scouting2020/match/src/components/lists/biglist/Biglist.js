import React, {Component} from "react";



export default class Biglist extends Component {

    generateItem(item){
        return(
            <li className="mdl-list__item mdl-list__item--three-line">
            <span className="mdl-list__item-primary-content">
            <i className="material-icons mdl-list__item-avatar">{item.avatar}</i>
        <span>{item.name}</span>
        <span className="mdl-list__item-text-body">
            {item.body}
    </span>
    </span>

            </li>
        );

    }
    generateInterior(items){
        const listItems = items.map((item) =>
this.generateItem(item)        );
        return listItems;
    }
    generateList(items){
        /*
        Expect format like follows:
        [{avatar:"person",name:"Item",body:"this is a body}]
         */
        return(

            <ul className="demo-list-three mdl-list">
                {this.generateInterior(items)}

            </ul>


        )


        }
    render() {
        return (this.generateList(this.props.items));
    }
}
