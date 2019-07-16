const CtlLayoutType=(props)=>{
	
	if(!props.attributes.layout){
		return null;
	}
	if(props.attributes.layout=="horizontal"){
		const horizontal_img=props.LayoutImgPath+"/cool-horizontal-timeline.jpg";
		const divStyle = {
			color: 'white',
			backgroundImage: 'url(' + horizontal_img + ')',
			height:'300px',
			width:'100%'
		  };
		return <div style={divStyle} className="ctl-block-image">
				<ul>
				<li><strong>Horizontal Timeline</strong></li>
				<li><strong>Layout:</strong> {props.attributes.layout}</li>
				<li><strong>Date Format:</strong> {props.attributes.dateformat}</li>
				<li><strong>Content Settings:</strong> {props.attributes.storycontent}</li>
				<li><strong>Order Settings:</strong> {props.attributes.order}</li>
				</ul>
		</div>;
	}else {
		const vertical_img=props.LayoutImgPath+"/cool-vertical-timeline.jpg";
		const divStylev = {
			color: 'white',
			backgroundImage: 'url(' + vertical_img + ')',
			height:'300px',
			width:'100%'
		  };
		return <div style={divStylev} className="ctl-block-image">
				<ul>
				<li><strong>Vertical Timeline</strong></li>
				<li><strong>Layout:</strong> {props.attributes.layout}</li>
				<li><strong>Skin:</strong> {props.attributes.skin}</li>
				<li><strong>Date Format:</strong> {props.attributes.dateformat}</li>
				<li><strong>Content Settings:</strong> {props.attributes.storycontent}</li>
				<li><strong>Order Settings:</strong> {props.attributes.order}</li>
				</ul>
		</div>;
	}	
}
export default CtlLayoutType;