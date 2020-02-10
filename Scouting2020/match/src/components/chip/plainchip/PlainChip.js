
import React, {Component} from "react";
export default class PlainChip extends Component{
    render(){
        return(
            <span class="mdl-chip">
    <span class="mdl-chip__text">{this.props.text}</span>
</span>

        );
    }
}
