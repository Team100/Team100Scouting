import React, {Component} from "react";


import "../../assets/css/materialIcons.css";
import "../../assets/css/matchScoutGlobal.css";
import {Form, Icon, Input, Button, Select, Row, Col, AutoComplete} from 'antd';
import 'antd/dist/antd.css';
import DataLayoutPage from "./DataLayoutPage";
import RunnerPage from "./RunnerPage";
import CommentPage from "./CommentPage";
import UploadPage from "./UploadPage"; // or 'antd/dist/antd.less'


export default class TopLevel extends Component {
    componentWillMount() {
        this.configCallback = this.configCallback.bind(this);
        this.runnerCallback = this.runnerCallback.bind(this);
        this.postMatchCallback = this.postMatchCallback.bind(this);

        this.setState({mode:"dataEntry"});
    }

    configCallback(params){
        this.setState({mode:"match",config:params});
    }
    runnerCallback(params){
        this.setState({mode:"postMatch",matchData:params});
    }
    postMatchCallback(params){
        this.setState({mode:"upload",postMatch:params})
    }
    render() {
        if(this.state.mode == "dataEntry"){
            return <DataLayoutPage callback={(props)=>this.configCallback(props)}/>
        }
        else if(this.state.mode == "match"){
            return <RunnerPage callback={(params)=>this.runnerCallback(params)}/>
        }
        else if(this.state.mode == "postMatch"){
            return(<CommentPage callback={(params)=>this.postMatchCallback(params)}/>)
        }
        else if(this.state.mode == "upload"){
            return <UploadPage data={this.state} />;
        }
        else{
            this.setState({mode:"dataEntry"});
            return(<p>Loading</p>);
        }
    }
}
