import React, {Component} from "react";
import "./BoldCard.css";
export default class BoldCard extends Component{
    render(){
        return(
            <div className="demo-card-event mdl-card mdl-shadow--2dp">
                <div className="mdl-card__title mdl-card--expand">
                    <h4>
                        {this.props.children}
                    </h4>
                </div>

            </div>

    );
    }
}
