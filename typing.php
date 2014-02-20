<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title>打字效果</title>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
</head>
 <body>

		<div id="autotype">

#include "3D_Object.h"<br/>
#include "3D_Plane.h"<br/>
#include "3D_Line.h"<br/>
#include "Space_Cal.h"<br/>
<br/>
using namespace std;<br/>
#ifdef _DEBUG<br/>
#pragma comment ( lib, "cxcore200d.lib" )<br/>
#pragma comment ( lib, "cv200d.lib" )<br/>
#pragma comment ( lib, "highgui200d.lib" )<br/>
#else<br/>
#pragma comment ( lib, "cxcore200.lib" )<br/>
#pragma comment ( lib, "cv200.lib" )<br/>
#pragma comment ( lib, "highgui200.lib" )<br/>
#endif<br/>
#include "cv.h"<br/>
#include "highgui.h"<br/>
<br/>
<br/>
#define X0 (g_ViewPoint.x)<br/>
#define Y0 (g_ViewPoint.y)<br/>
#define Z0 (g_ViewPoint.z)<br/>
#define eye_focal_distance 1<br/>
#define PI 3.14159265358979f<br/>
#define FRAME_HEIGTH 400<br/>
#define FRAME_WIDTH 400<br/>
#define PIXEL_RATE 200<br/>
#define  WINDOW_NAME "3D Model"<br/>
<br/>
<br/>
//1  正方体   2  长方体   3  椎体    4   面<br/>
#define OBJECT_CHOOSE 1<br/>
<br/>
IplImage* frame = NULL;<br/>
_3DPoint g_ViewPoint(4,2,0);//视点<br/>
_3DObject g_Object;//观测的物体<br/>
<br/>
const float weitiao = 0.2f;<br/>
int mouse_begin_x;<br/>
int mouse_begin_y;<br/>
int mouse_counter;<br/>


void InitObject();//初始化观测物体的参数<br/>
void on_mouse( int event, int x, int y, int flags, void* param );//鼠标响应函数<br/>
void Draw_3D_Object();//画3D图形<br/>
void DrawDotLine(CvPoint point1,CvPoint point2);//画虚线<br/>
void SetInvisiablePoint();//设置不可见的点<br/>
//获取视网膜平面<br/>
_3DPlane GetRetinaPlane()<br/>
{<br/>
	return _3DPlane(X0,Y0,Z0,-X0*X0-Y0*Y0-Z0*Z0-eye_focal_distance*sqrt(X0*X0+Y0*Y0+Z0*Z0));<br/>
}<br/>
<br/>
//获取视网膜平面原点空间坐标<br/>
_3DPoint GetOriPoint_In_RetinaPlane()<br/>
{<br/>
	float Dis = GetDistance_From_TowPoint(_3DPoint(0,0,0),g_ViewPoint);<br/>
	float k = (Dis+eye_focal_distance)/Dis;<br/>
	return _3DPoint(X0*k,Y0*k,Z0*k);<br/>
}<br/>
<br/>
//视网膜平面定义的X轴<br/>
_3DVector GetRetinaXAxis()<br/>
{<br/>
	_3DVector X_Axis = Cross(g_ViewPoint,_3DVector(0,0,1));<br/>
	//归一化<br/>
	float m = GetDistance_From_TowPoint(_3DPoint(0,0,0),X_Axis);<br/>
	X_Axis.x /= m;<br/>
	X_Axis.y /= m;<br/>
	X_Axis.z /= m;<br/>
	return X_Axis;<br/>
}<br/>



//返回值前两个坐标有效，表示二维平面下的坐标<br/>
_3DPoint Get2DPoint(_3DPlane RetinaPlane,_3DPoint _OriPoint,_3DPoint<br/> RetinaOriPoint,_3DVector XAxis,_3DVector YAxis)<br/>
{	<br/>
	_3DLine Line = GetLine_From_TwoPoint(g_ViewPoint,_OriPoint);<br/>
	_3DPoint Point_In_Retina = GetPoint_From_Line_And_Plane(Line,RetinaPlane);<br/>
<br/>
	_3DVector temp = _3DVector(Point_In_Retina.x-RetinaOriPoint.x,<br/>
		Point_In_Retina.y-RetinaOriPoint.y,<br/>
		Point_In_Retina.z-RetinaOriPoint.z);<br/>
<br/>
	return _3DPoint(Dot(temp,XAxis),Dot(temp,YAxis),0);<br/>
}<br/>



int main(int argc,char* argv[])<br/>
{<br/>
	InitObject();<br/>
	frame = cvCreateImage(cvSize(FRAME_WIDTH,FRAME_HEIGTH),IPL_DEPTH_8U,3);<br/>
	cvNamedWindow(WINDOW_NAME);<br/>
	cvSetMouseCallback(WINDOW_NAME, on_mouse, 0 );<br/>
	Draw_3D_Object();<br/>
	while(1)<br/>
	{<br/>
		cvShowImage(WINDOW_NAME,frame);<br/>
		cvWaitKey(2);<br/>
	}<br/>
	return 0;<br/>
}<br/>


		</div>
</body>
</html>
<style>
body{
	background:#000;
}

#autotype{
color: #83f352;
font-family: Bitwise, monospace;
font-weight: bold;
text-shadow: 0.1em 0.1em 0.2em #83f352;
cursor: none;
font-size:15px;
}
</style>
<script>
$tt = $("#autotype");
var str = $tt.html();
var index = 0;
$("#autotype").html('');

$.fn.autotype = function() {
	var current = str.substr(index, 1);

	if (current == '<') 
    index = str.indexOf('>', index) + 1;
  else 
    index++;
				
  $tt.html(str.substring(0, index) + (index & 1 ? '_' : ''));
};

document.onkeydown = function (e) {
$("#autotype").autotype();
}
</script>