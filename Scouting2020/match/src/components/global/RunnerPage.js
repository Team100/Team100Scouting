import React, {Component} from "react";

import "material-design-lite/material.min";
import "material-design-lite/material.min.css";
import "../../assets/css/materialIcons.css";
import "../../assets/css/matchScoutGlobal.css";
import EventListener, {withOptions} from 'react-event-listener';

export default class RunnerPage extends Component{
    configURL = "https://storage.googleapis.com/alpha.cdn.atco.mp/ScoutingGenerationSchema.json";

    state={};

    componentWillMount() {
       this.updateConfig()
    }

    navigateToPage(page){
        this.setState({page:page});
        console.info("PAGE "+page);

    }
    updateConfig(){

        fetch(this.configURL)
            .then(response => response.json())
            .then(data => {console.info(data); return data;})
            .then(data => this.setState({ config : data }))
            .then(() => console.info("Logged"))
            .then(() => console.log(this.state));

    }
    handleKeyPress = (event) => {
        console.log(event.key);
    }

    render(){
        if(this.state.page == null){
            this.navigateToPage(0)
        }
        if(this.state.config == null) {
            return (<p>Loading</p>);
        }

        else{
                var menuItems = this.state.config.ui.pages;

                var menu = menuItems.map((item)=>
                    <div className={"sidebarItem"} onClick={()=>this.navigateToPage(item.btn)}><a key={item.btn} className={"sidebarButton"}>{item.name} ({item.btn})</a></div>
                );
                return(

                    <div className={"page"}>
                        <EventListener onKeyDown={this.handleKeyPress} target="window"/>
                        <div className={"sidebar"}>

                            {menu}
                        </div>
                        <div className={"pageContent"}>
                            <p>{this.state.page}</p>
                        </div>

                    </div>


                );

        }


    }



}
